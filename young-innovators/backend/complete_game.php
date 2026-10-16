<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$courseId = (int)post('course_id');

if (!$courseId) {
    jsonError('course_id required');
}

$pdo = db();

$pdo->beginTransaction();

try {

    // Update progress

    $stmt = $pdo->prepare("
        UPDATE enrollments
        SET progress = 100
        WHERE student_id = ?
        AND course_id = ?
        AND status = 'enrolled'
    ");

    $stmt = $pdo->prepare("
    SELECT progress
    FROM enrollments
    WHERE student_id = ?
    AND course_id = ?
");

    $stmt->execute([
        $user['id'],
        $courseId
    ]);

    $alreadyCompleted = $stmt->fetchColumn();

    if ($alreadyCompleted >= 100) {

        jsonOk([
            'message' => 'XP already awarded'
        ]);

        exit;
    }

    $stmt->execute([
        $user['id'],
        $courseId
    ]);

    // Get course skill

    $stmt = $pdo->prepare("
        SELECT skill_id
        FROM courses
        WHERE id = ?
    ");

    $stmt->execute([$courseId]);

    $skillId = (int)$stmt->fetchColumn();

    if ($skillId) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM student_badges
            WHERE student_id = ?
            AND skill_id = ?
        ");

        $stmt->execute([
            $user['id'],
            $skillId
        ]);

        $existing = $stmt->fetch();

        if ($existing) {

            $pdo->prepare("
                UPDATE student_badges
                SET xp = xp + 100
                WHERE student_id = ?
                AND skill_id = ?
            ")->execute([
                $user['id'],
                $skillId
            ]);
        } else {

            $pdo->prepare("
                INSERT INTO student_badges
                (
                    student_id,
                    skill_id,
                    level,
                    xp
                )
                VALUES
                (
                    ?,
                    ?,
                    1,
                    50
                )
            ")->execute([
                $user['id'],
                $skillId
            ]);
        }
    }

    $pdo->commit();

    jsonOk([
        'message' => 'Game completed'
    ]);
} catch (Exception $e) {

    $pdo->rollBack();

    jsonError($e->getMessage());
}
