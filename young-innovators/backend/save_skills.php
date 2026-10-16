<?php
// ============================================================
// api/save_skills.php
// POST skills=Math,Science,Coding  (student onboarding)
// ============================================================
require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$skills = post('skills'); // comma-separated skill names
if (!$skills) jsonError('skills required');

$pdo = db();

// Clear existing, then re-insert
$pdo->prepare("DELETE FROM student_skills WHERE student_id=?")->execute([$user['id']]);

$insert = $pdo->prepare("
    INSERT IGNORE INTO student_skills (student_id, skill_id)
    SELECT ?, id FROM skills WHERE name = ?
");
foreach (explode(',', $skills) as $s) {
    $s = trim($s);
    if ($s) $insert->execute([$user['id'], $s]);
}

// Mark onboarded
$pdo->prepare("UPDATE students SET onboarded=1 WHERE user_id=?")->execute([$user['id']]);

jsonOk(['message' => 'Interests saved.']);
