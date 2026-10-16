<?php
// ============================================================
// api/enrollment.php
// POST action=enroll|approve|deny|list_pending
// Students enroll (status=pending_parent).
// Parents approve or deny.
// ============================================================
require_once __DIR__ . '/../config.php';

$action = post('action') ?: get('action');

switch ($action) {
    case 'enroll':
        handleEnroll();
        break;
    case 'approve':
        handleApprove();
        break;
    case 'deny':
        handleDeny();
        break;
    case 'list_pending':
        listPending();
        break;
    case 'my_courses':
        myCourses();
        break;
    default:
        jsonError('Unknown action');
}

// ── Student requests enrollment ──────────────────────────────
function handleEnroll(): void
{

    $user     = requireAuth('student');
    $courseId = (int)post('course_id');

    if (!$courseId) {
        jsonError('course_id required');
    }

    $pdo = db();

    // Course exists
    $c = $pdo->prepare("
        SELECT id
        FROM courses
        WHERE id=? AND status='approved'
    ");

    $c->execute([$courseId]);

    if (!$c->fetch()) {
        jsonError('Course not available');
    }

    // Prevent duplicates
    $dup = $pdo->prepare("
        SELECT id
        FROM enrollments
        WHERE student_id=? AND course_id=?
    ");

    $dup->execute([$user['id'], $courseId]);

    if ($dup->fetch()) {
        jsonError('Already enrolled');
    }

    // DIRECT ENROLLMENT
    $pdo->prepare("
        INSERT INTO enrollments
        (student_id, course_id, status)
        VALUES (?, ?, 'enrolled')
    ")->execute([$user['id'], $courseId]);

    // =====================================================
    // Parent Chat Room Logic
    // =====================================================

    // Find student's parent
    $parentStmt = $pdo->prepare("
    SELECT parent_id
    FROM students
    WHERE user_id = ?
");
    $parentStmt->execute([$user['id']]);

    $parentId = $parentStmt->fetchColumn();

    if ($parentId) {

        // Check if room already exists for this course
        $roomStmt = $pdo->prepare("
        SELECT id
        FROM parent_chat_rooms
        WHERE course_id = ?
        LIMIT 1
    ");

        $roomStmt->execute([$courseId]);

        $roomId = $roomStmt->fetchColumn();

        // Create room if none exists
        if (!$roomId) {

            $pdo->prepare("
            INSERT INTO parent_chat_rooms
            (course_id)
            VALUES (?)
        ")->execute([$courseId]);

            $roomId = $pdo->lastInsertId();
            $pdo->prepare("
            INSERT INTO parent_chat_messages
            (room_id, parent_id, message)
            VALUES (?, ?, ?)
            ")->execute([
                $roomId,
                $parentId,
                'Welcome to the parent community for this course!'
            ]);
        }

        // Add parent to room if not already joined
        $exists = $pdo->prepare("
            SELECT 1
            FROM parent_chat_room_members
            WHERE room_id = ?
            AND parent_id = ?
        ");

        $exists->execute([
            $roomId,
            $parentId
        ]);

        if (!$exists->fetch()) {

            $pdo->prepare("
            INSERT INTO parent_chat_room_members
            (room_id, parent_id)
            VALUES (?, ?)
        ")->execute([
                $roomId,
                $parentId
            ]);
        }
    }

    jsonOk([
        'message' => 'Successfully enrolled!'
    ]);
}

// ── Parent approves enrollment ───────────────────────────────
function handleApprove(): void
{
    $parent      = requireAuth('parent');
    $enrollmentId = (int)post('enrollment_id');
    if (!$enrollmentId) jsonError('enrollment_id required');

    $pdo = db();

    // Verify the enrollment belongs to one of this parent's children
    $chk = $pdo->prepare("
        SELECT e.id FROM enrollments e
        JOIN students s ON s.user_id = e.student_id
        WHERE e.id = ? AND s.parent_id = ? AND e.status = 'pending_parent'
    ");
    $chk->execute([$enrollmentId, $parent['id']]);
    if (!$chk->fetch()) jsonError('Enrollment not found or already processed');

    $pdo->prepare("UPDATE enrollments SET status='enrolled' WHERE id=?")
        ->execute([$enrollmentId]);

    jsonOk(['message' => 'Enrollment approved.']);
}

// ── Parent denies enrollment ─────────────────────────────────
function handleDeny(): void
{
    $parent       = requireAuth('parent');
    $enrollmentId = (int)post('enrollment_id');
    if (!$enrollmentId) jsonError('enrollment_id required');

    $pdo = db();
    $chk = $pdo->prepare("
        SELECT e.id FROM enrollments e
        JOIN students s ON s.user_id = e.student_id
        WHERE e.id = ? AND s.parent_id = ? AND e.status = 'pending_parent'
    ");
    $chk->execute([$enrollmentId, $parent['id']]);
    if (!$chk->fetch()) jsonError('Enrollment not found or already processed');

    $pdo->prepare("UPDATE enrollments SET status='rejected' WHERE id=?")
        ->execute([$enrollmentId]);

    jsonOk(['message' => 'Enrollment rejected.']);
}

// ── List pending enrollments for parent ──────────────────────
function listPending(): void
{
    $parent = requireAuth('parent');
    $pdo    = db();
    $stmt   = $pdo->prepare("
        SELECT e.id AS enrollment_id, e.course_id,
               c.title AS course_title,
               u.display_name AS student_name,
               s.child_id
        FROM enrollments e
        JOIN students s ON s.user_id = e.student_id
        JOIN users u    ON u.id = s.user_id
        JOIN courses c  ON c.id = e.course_id
        WHERE s.parent_id = ? AND e.status = 'pending_parent'
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([$parent['id']]);
    jsonOk(['pending' => $stmt->fetchAll()]);
}

// ── Student's enrolled courses ───────────────────────────────
function myCourses(): void
{
    $user = requireAuth('student');
    $pdo  = db();
    $stmt = $pdo->prepare("
        SELECT e.id AS enrollment_id, e.status, e.progress,
               c.id AS course_id, c.title, c.description, c.type,
               c.game_type, c.game_page, c.csv_file,
               sk.name AS skill_name
        FROM enrollments e
        JOIN courses c ON c.id = e.course_id
        LEFT JOIN skills sk ON sk.id = c.skill_id
        WHERE e.student_id = ?
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([$user['id']]);
    jsonOk(['courses' => $stmt->fetchAll()]);
}
