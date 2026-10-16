<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$pdo = db();

$courseId = (int)post('course_id');
$progress = (int)post('progress');

if (!$courseId) {
    jsonError('course_id required');
}

if ($progress < 0 || $progress > 100) {
    jsonError('Invalid progress');
}

$stmt = $pdo->prepare("
    UPDATE enrollments
    SET progress = ?
    WHERE student_id = ? AND course_id = ?
");

$stmt->execute([
    $progress,
    $user['id'],
    $courseId
]);

jsonOk([
    'message' => 'Progress updated'
]);