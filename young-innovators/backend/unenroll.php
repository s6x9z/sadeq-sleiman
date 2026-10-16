<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$pdo = db();

$courseId = (int)post('course_id');

if (!$courseId) {
    jsonError('course_id required');
}

$pdo->prepare("
    DELETE FROM enrollments
    WHERE student_id=? AND course_id=?
")->execute([
    $user['id'],
    $courseId
]);

jsonOk([
    'message' => 'Unenrolled successfully'
]);