<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('parent');

$pdo = db();

$stmt = $pdo->prepare("
SELECT
    u.display_name AS student_name,
    CONCAT('Completed a game') AS activity,
    ga.score AS xp,
    DATE_FORMAT(ga.played_at, '%Y-%m-%d') AS activity_date
FROM students s
JOIN users u
    ON u.id = s.user_id
LEFT JOIN game_analytics ga
    ON ga.student_id = s.user_id
WHERE s.parent_id = ?
ORDER BY ga.played_at DESC
LIMIT 20
");

$stmt->execute([
    $user['id']
]);

jsonOk([
    'activities' => $stmt->fetchAll()
]);