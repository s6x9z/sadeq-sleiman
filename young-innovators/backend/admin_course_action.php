<?php

require_once __DIR__ . '/../config.php';

$admin = requireAuth('admin');

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$courseId = (int)($data['course_id'] ?? 0);

$action = $data['action'] ?? '';

if(!$courseId)
    jsonError('Invalid course');

$pdo = db();

if($action === 'approve'){

    $stmt = $pdo->prepare("
    UPDATE courses
    SET status='approved'
    WHERE id=?
    ");

    $stmt->execute([$courseId]);

    jsonOk([
        'message'=>'Course approved'
    ]);
}

if($action === 'reject'){

    $stmt = $pdo->prepare("
    UPDATE courses
    SET status='rejected'
    WHERE id=?
    ");

    $stmt->execute([$courseId]);

    jsonOk([
        'message'=>'Course rejected'
    ]);
}

jsonError('Invalid action');