<?php
// ============================================================
// api/get_badges.php
// GET ?student_id=X (optional, admin/parent use)
//     otherwise uses session student
// Returns student badges with XP progress
// ============================================================
require_once __DIR__ . '/../config.php';

$user = requireAuth('student', 'parent', 'admin');

// Determine which student to look up
if ($user['role'] === 'student') {
    $studentId = $user['id'];
} else {
    $studentId = (int)get('student_id');
    if (!$studentId) jsonError('student_id required');

    // Parent can only view own children
    if ($user['role'] === 'parent') {
        $chk = db()->prepare("SELECT user_id FROM students WHERE user_id=? AND parent_id=?");
        $chk->execute([$studentId, $user['id']]);
        if (!$chk->fetch()) jsonError('Not your child', 403);
    }
}

$pdo  = db();
$stmt = $pdo->prepare("
    SELECT sb.skill_id, sb.level, sb.xp,
           sk.name AS skill_name, sk.badge_name, sk.badge_icon,
           CASE
               WHEN sb.xp < 100  THEN 100  - sb.xp
               WHEN sb.xp < 300  THEN 300  - sb.xp
               WHEN sb.xp < 600  THEN 600  - sb.xp
               WHEN sb.xp < 1000 THEN 1000 - sb.xp
               ELSE 0
           END AS xp_to_next
    FROM student_badges sb
    JOIN skills sk ON sk.id = sb.skill_id
    WHERE sb.student_id = ?
    ORDER BY sb.xp DESC
");
$stmt->execute([$studentId]);
$badges = $stmt->fetchAll();

// Total XP
$total = array_sum(array_column($badges, 'xp'));

// Student streak
$streakStmt = $pdo->prepare("
    SELECT daily_streak
    FROM students
    WHERE user_id = ?
");
$streakStmt->execute([$studentId]);

$dailyStreak = (int)($streakStmt->fetchColumn() ?: 0);

jsonOk([
    'badges' => $badges,
    'total_xp' => $total,
    'daily_streak' => $dailyStreak
]);
