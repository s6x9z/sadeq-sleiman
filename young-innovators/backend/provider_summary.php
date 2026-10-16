<?php
require_once __DIR__ . '/../config.php';
$user = requireAuth('provider');
$pdo = db();
$courses = $pdo->prepare("SELECT COUNT(*) FROM courses WHERE provider_id=?");
$courses->execute([$user['id']]);
$uploads = $pdo->prepare("SELECT COUNT(*) FROM provider_uploads WHERE provider_id=?");
$uploads->execute([$user['id']]);
$students = $pdo->prepare("SELECT COUNT(DISTINCT e.student_id) FROM enrollments e JOIN courses c ON c.id=e.course_id WHERE c.provider_id=? AND e.status='enrolled'");
$students->execute([$user['id']]);
jsonOk(['summary' => [
  'courses' => (int)$courses->fetchColumn(),
  'uploads' => (int)$uploads->fetchColumn(),
  'students' => (int)$students->fetchColumn(),
  'games' => 0
]]);
