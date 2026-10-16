<?php
require_once __DIR__ . '/../config.php';

$user = requireAuth('provider');
$pdo = db();

$title = post('title') ?: pathinfo($_FILES['pdf']['name'] ?? 'Uploaded Course', PATHINFO_FILENAME);
$subject = post('subject');
$description = post('description');
if (!$subject) jsonError('Subject is required');
if (empty($_FILES['pdf']['tmp_name'])) jsonError('PDF file is required');
$ext = strtolower(pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') jsonError('Only PDF files are allowed');

$dir = __DIR__ . '/../uploads/pdfs/';
if (!is_dir($dir)) mkdir($dir, 0775, true);
$fileName = 'pdf_' . bin2hex(random_bytes(8)) . '.pdf';
if (!move_uploaded_file($_FILES['pdf']['tmp_name'], $dir . $fileName)) jsonError('Could not save PDF');
$filePath = '/app/backend/uploads/pdfs/' . $fileName;

$skill = $pdo->prepare("SELECT id FROM skills WHERE name=? LIMIT 1");
$skill->execute([$subject]);
$skillId = (int)($skill->fetchColumn() ?: 0) ?: null;

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("INSERT INTO courses (provider_id, title, description, skill_id, type, status) VALUES (?, ?, ?, ?, 'game', 'approved')");
    $stmt->execute([$user['id'], $title, $description, $skillId]);
    $courseId = (int)$pdo->lastInsertId();
    $pdo->prepare("INSERT INTO provider_uploads (provider_id, course_id, file_name, file_path, subject, status) VALUES (?, ?, ?, ?, ?, 'uploaded')")
        ->execute([$user['id'], $courseId, $_FILES['pdf']['name'], $filePath, $subject]);
    $pdo->commit();
} catch (Throwable $e) {
    $pdo->rollBack();
    jsonError('Could not save upload');
}

jsonOk(['message' => 'PDF uploaded. Course is now available.', 'course_id' => $courseId]);
