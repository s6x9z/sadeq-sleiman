<?php

require_once __DIR__ . '/../config.php';

$pdo = db();

$stmt = $pdo->query("
SELECT
    u.display_name,
    COALESCE(SUM(sb.xp),0) AS total_xp

FROM users u

JOIN students s
    ON s.user_id = u.id

LEFT JOIN student_badges sb
    ON sb.student_id = s.user_id

GROUP BY u.id

ORDER BY total_xp DESC

LIMIT 10
");

jsonOk([
    'students' => $stmt->fetchAll()
]);