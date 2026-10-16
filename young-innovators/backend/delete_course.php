<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('provider');

$data = json_decode(
    file_get_contents("php://input"),
    true
);

$id = (int)($data['id'] ?? 0);

if(!$id)
    jsonError('Invalid course');

$pdo = db();

$stmt = $pdo->prepare("
DELETE FROM courses
WHERE id = ?
AND provider_id = ?
");

$stmt->execute([
    $id,
    $user['id']
]);

jsonOk([
    'message' => 'Course deleted'
]);