<?php
// ============================================================
// api/courses.php – unified course management
// POST action=submit|approve|reject|delete|publish|unpublish|list
// GET  action=list|get&id=X
// ============================================================
require_once __DIR__ . '/../config.php';

$action = post('action') ?: get('action');

switch ($action) {
    case 'submit':
        submitCourse();
        break;
    case 'delete':
        deleteCourse();
        break;
    case 'list':
        listCourses();
        break;
    case 'get':
        getCourse();
        break;
    default:
        jsonError('Unknown action');
}

// ── Provider submits a course ────────────────────────────────
function submitCourse(): void
{
    $user = requireAuth('provider');
    $pdo  = db();

    $title   = post('title');
    $desc    = post('description');
    $skillId = (int)post('skill_id') ?: null;
    $skillName = post('skill_name');
    if (!$skillId && $skillName) {
        $s = $pdo->prepare("SELECT id FROM skills WHERE name=? LIMIT 1");
        $s->execute([$skillName]);
        $skillId = (int)($s->fetchColumn() ?: 0) ?: null;
    }
    $ageMin  = (int)post('age_min') ?: 5;
    $ageMax  = (int)post('age_max') ?: 18;
    $type = post('type') ?: 'game';

    $gameType = post('game_type');
    $gamePage = post('game_page');
    $csvFile  = post('csv_file');

    if (!$title) jsonError('title required');
    if (!in_array($type, ['video', 'quiz', 'game'], true)) jsonError('type must be video, quiz, or game');

    $pdo->prepare("
    INSERT INTO courses
    (
        provider_id,
        title,
        description,
        skill_id,
        game_type,
        game_page,
        csv_file,
        age_min,
        age_max,
        type,
        status
    )
    VALUES
    (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved'
    )
")->execute([
        $user['id'],
        $title,
        $desc,
        $skillId,
        $gameType,
        $gamePage,
        $csvFile,
        $ageMin,
        $ageMax,
        $type
    ]);

    jsonOk([
        'course_id' => (int)$pdo->lastInsertId(),
        'message' => 'Course published successfully.'
    ]);
}

// ── Provider deletes a course ─────────────────────────────────
function deleteCourse(): void
{
    $user = requireAuth('provider');

    $id = (int)post('course_id');

    if (!$id) {
        jsonError('course_id required');
    }

    $pdo = db();

    $stmt = $pdo->prepare("
        DELETE FROM courses
        WHERE id = ?
        AND provider_id = ?
    ");

    $stmt->execute([
        $id,
        $user['id']
    ]);

    jsonOk([
        'message' => 'Course deleted.'
    ]);
}

// ── List courses (public: approved; admin: all; provider: own) ─
function listCourses(): void
{
    $pdo  = db();
    $user = currentUser();
    $sql  = "
    SELECT c.id,
           c.title,
           c.description,
           c.type,
           c.status,
           c.game_type,
           c.game_page,
           c.csv_file,
           c.age_min,
           c.age_max,
           c.created_at,
           sk.name AS skill_name,
           sk.badge_icon,
           u.display_name AS provider_name,
           COUNT(DISTINCT e.student_id) AS total_students,
           COUNT(DISTINCT ga.id) AS total_games_played,
           ROUND(AVG(ga.score),0) AS avg_score

    FROM courses c

    LEFT JOIN skills sk
           ON sk.id = c.skill_id

    LEFT JOIN users u
           ON u.id = c.provider_id

    LEFT JOIN enrollments e
           ON e.course_id = c.id

    LEFT JOIN game_analytics ga
       ON ga.course_id = c.id
";

    if ($user && $user['role'] === 'admin') {
        $stmt = $pdo->prepare(
            $sql .
                " GROUP BY c.id
          ORDER BY c.created_at DESC"
        );
        $stmt->execute();
    } elseif ($user && $user['role'] === 'provider') {
        $stmt = $pdo->prepare(
            $sql .
                " WHERE c.provider_id = ?
                  GROUP BY c.id
                  ORDER BY c.created_at DESC"
        );
        $stmt->execute([$user['id']]);
    } else {
        // Public: only approved courses
        $skillFilter = get('skill');
        $ageFilter   = (int)get('age');
        $where = " WHERE c.status = 'approved'";
        $params = [];
        if ($skillFilter) {
            $where .= " AND sk.name = ?";
            $params[] = $skillFilter;
        }
        if ($ageFilter) {
            $where .= " AND c.age_min <= ? AND c.age_max >= ?";
            $params[] = $ageFilter;
            $params[] = $ageFilter;
        }
        $stmt = $pdo->prepare(
            $sql .
                $where .
                " GROUP BY c.id
      ORDER BY c.created_at DESC"
        );
        $stmt->execute($params);
    }

    jsonOk(['courses' => $stmt->fetchAll()]);
}

// ── Get single course ────────────────────────────────────────
function getCourse(): void
{
    $id  = (int)get('id');
    if (!$id) jsonError('id required');
    $pdo = db();
    $stmt = $pdo->prepare("
        SELECT c.*, sk.name AS skill_name, u.display_name AS provider_name
        FROM courses c
        LEFT JOIN skills sk ON sk.id = c.skill_id
        LEFT JOIN users u   ON u.id  = c.provider_id
        WHERE c.id = ?
    ");
    $stmt->execute([$id]);
    $course = $stmt->fetch();
    if (!$course) jsonError('Not found', 404);

    // Modules
    $mods = $pdo->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY order_num");
    $mods->execute([$id]);
    $course['modules'] = $mods->fetchAll();

    jsonOk(['course' => $course]);
}
