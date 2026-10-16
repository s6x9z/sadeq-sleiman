<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$courseId = (int)post('course_id');
$score = (int)post('score');
$completed = (int)post('completed');

if (!$courseId) {
    jsonError('course_id required');
}

$pdo = db();

$stmt = $pdo->prepare("
    INSERT INTO game_analytics
    (
        course_id,
        student_id,
        score,
        completed
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?
    )
");

$stmt->execute([
    $courseId,
    $user['id'],
    $score,
    $completed
]);

jsonOk([
    'message' => 'analytics saved'
]);
