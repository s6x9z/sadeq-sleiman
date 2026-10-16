<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$pdo = db();

$stmt = $pdo->prepare("
SELECT
    sk.badge_name,
    sb.xp,
    sb.level
FROM student_badges sb
JOIN skills sk ON sk.id = sb.skill_id
WHERE sb.student_id = ?
ORDER BY sb.id DESC
LIMIT 10
");

$stmt->execute([$user['id']]);

jsonOk([
    'activities' => $stmt->fetchAll()
]);
