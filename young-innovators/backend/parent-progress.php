<?php

require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$pdo = db();

$stmt = $pdo->prepare("
    SELECT
        u.display_name AS student_name,
        c.title,
        e.progress

    FROM enrollments e

    JOIN students s
        ON s.user_id = e.student_id

    JOIN users u
        ON u.id = s.user_id

    JOIN courses c
        ON c.id = e.course_id

    WHERE s.parent_id = ?

    ORDER BY e.enrolled_at DESC
");

$stmt->execute([
    $parent['id']
]);

jsonOk([
    'courses' => $stmt->fetchAll()
]);