<?php
// ============================================================
// api/complete_module.php
// POST course_id=X accuracy=95 time_ms=12000
// Awards XP if accuracy >= 90, updates enrollment progress
// ============================================================
require_once __DIR__ . '/../config.php';

$user     = requireAuth('student');
$courseId = (int)post('course_id');
$accuracy = (float)post('accuracy');
$timeMs   = (int)post('time_ms');

if (!$courseId) jsonError('course_id required');

$pdo = db();

// Verify enrolled
$enr = $pdo->prepare("SELECT id, progress FROM enrollments WHERE student_id=? AND course_id=? AND status='enrolled'");
$enr->execute([$user['id'], $courseId]);
$enrollment = $enr->fetch();
if (!$enrollment) jsonError('Not enrolled in this course');

// Record attempt
$pdo->prepare("
    INSERT INTO course_attempts (student_id, course_id, accuracy, time_ms)
    VALUES (?, ?, ?, ?)
")->execute([$user['id'], $courseId, $accuracy, $timeMs]);

$xpAwarded = 0;

// Award XP if accuracy >= 90%
if ($accuracy >= 90) {
    // Get course skill_id
    $c = $pdo->prepare("SELECT skill_id FROM courses WHERE id=?");
    $c->execute([$courseId]);
    $course = $c->fetch();
    if ($course && $course['skill_id']) {
        $xpAwarded = (int)($accuracy >= 100 ? 50 : 30);
        awardXP($user['id'], (int)$course['skill_id'], $xpAwarded);
    }
}

// Update enrollment progress (increment by 1 module)
$totalModules = (int)$pdo->prepare("SELECT COUNT(*) FROM modules WHERE course_id=?")->execute([$courseId]);
$newProgress  = min(100, (int)$enrollment['progress'] + 10); // simplistic 10% per attempt

$pdo->prepare("UPDATE enrollments SET progress=? WHERE id=?")
    ->execute([$newProgress, $enrollment['id']]);

// If 100% complete, mark enrollment completed
if ($newProgress >= 100) {
    $pdo->prepare("UPDATE enrollments SET status='completed' WHERE id=?")->execute([$enrollment['id']]);
}

jsonOk([
    'xp_awarded' => $xpAwarded,
    'progress'   => $newProgress,
    'message'    => $xpAwarded > 0 ? "Great job! +{$xpAwarded} XP earned." : 'Module completed.',
]);
