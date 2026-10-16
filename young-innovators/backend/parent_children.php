<?php
// ============================================================
// api/parent_children.php
// GET → returns all children for the logged-in parent
// ============================================================
require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$pdo  = db();
$stmt = $pdo->prepare("
    SELECT
        u.id, u.username, u.display_name, u.first_name, u.last_name, u.birthdate,
        s.child_id, s.age, s.daily_streak, s.last_login, s.onboarded,
        (SELECT COUNT(*) FROM enrollments e WHERE e.student_id = s.user_id AND e.status = 'enrolled') AS course_count,
        (SELECT COALESCE(SUM(sb.xp),0) FROM student_badges sb WHERE sb.student_id = s.user_id) AS total_xp,
        (SELECT COUNT(*) FROM student_badges sb2 WHERE sb2.student_id = s.user_id) AS badge_count
    FROM students s
    JOIN users u ON u.id = s.user_id
    WHERE s.parent_id = ?
    ORDER BY u.first_name
");
$stmt->execute([$parent['id']]);
$children = $stmt->fetchAll();

jsonOk(['children' => $children]);
