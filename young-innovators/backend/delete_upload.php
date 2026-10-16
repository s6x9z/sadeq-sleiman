<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('provider');
$pdo = db();

$uploadId = (int)post('upload_id');

if (!$uploadId) {
    jsonError('upload_id required');
}

// Get upload
$stmt = $pdo->prepare("
    SELECT file_path, course_id
    FROM provider_uploads
    WHERE id=? AND provider_id=?
");

$stmt->execute([$uploadId, $user['id']]);

$upload = $stmt->fetch();

if (!$upload) {
    jsonError('Upload not found');
}

// Delete physical file
$fullPath = dirname(__DIR__) . str_replace('/app/backend', '', $upload['file_path']);

if (file_exists($fullPath)) {
    unlink($fullPath);
}

// Delete upload
$pdo->prepare("
    DELETE FROM provider_uploads
    WHERE id=?
")->execute([$uploadId]);

// Delete course
$pdo->prepare("
    DELETE FROM courses
    WHERE id=? AND provider_id=?
")->execute([$upload['course_id'], $user['id']]);

jsonOk([
    'message' => 'Upload deleted successfully'
]);