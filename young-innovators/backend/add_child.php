<?php
require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$firstName = post('first_name');
$lastName  = post('last_name');
$username  = strtolower(post('username'));
$password  = post('password');
$birthdate = post('birthdate');
$interests = post('interests');

if (!$firstName || !$lastName || !$username || !$password) {
    jsonError('First name, last name, username, and password are required');
}

if (
    strlen($password) < 8 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[a-z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[^A-Za-z0-9]/', $password)
) {
    jsonError(
        'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.'
    );
}

$birthdateValue = null;
$age = null;

if ($birthdate) {
    try {
        $birthDateObj = new DateTime($birthdate);
        $today = new DateTime();

        if ($birthDateObj > $today) {
            jsonError('Birthdate cannot be in the future.');
        }

        $birthdateValue = $birthDateObj->format('Y-m-d');
        $age = $today->diff($birthDateObj)->y;
    } catch (Throwable $e) {
        jsonError('Invalid birthdate.');
    }
}

$pdo = db();

$check = $pdo->prepare("
    SELECT id
    FROM users
    WHERE username = ?
    LIMIT 1
");
$check->execute([$username]);

if ($check->fetch()) {
    jsonError('Username already taken');
}

do {
    $childId = 'kid_' . substr(bin2hex(random_bytes(4)), 0, 6);

    $dup = $pdo->prepare("
        SELECT user_id
        FROM students
        WHERE child_id = ?
        LIMIT 1
    ");
    $dup->execute([$childId]);
} while ($dup->fetch());

$displayName = trim($firstName . ' ' . $lastName);

$pdo->beginTransaction();

try {

    $stmt = $pdo->prepare("
        INSERT INTO users (
            email,
            username,
            password_hash,
            role,
            display_name,
            first_name,
            last_name,
            birthdate
        )
        VALUES (
            NULL,
            ?,
            ?,
            'student',
            ?,
            ?,
            ?,
            ?
        )
    ");

    $stmt->execute([
        $username,
        password_hash($password, PASSWORD_DEFAULT),
        $displayName,
        $firstName,
        $lastName,
        $birthdateValue
    ]);

    $userId = (int)$pdo->lastInsertId();

    $stmt = $pdo->prepare("
        INSERT INTO students (
            user_id,
            child_id,
            parent_id,
            age,
            onboarded
        )
        VALUES (?, ?, ?, ?, 1)
    ");

    $stmt->execute([
        $userId,
        $childId,
        $parent['id'],
        $age
    ]);

    if ($interests) {

        $skillInsert = $pdo->prepare("
            INSERT IGNORE INTO student_skills (
                student_id,
                skill_id
            )
            SELECT ?, id
            FROM skills
            WHERE name = ?
        ");

        foreach (explode(',', $interests) as $interest) {
            $interest = trim($interest);

            if ($interest) {
                $skillInsert->execute([
                    $userId,
                    $interest
                ]);
            }
        }
    }

    $pdo->commit();

    jsonOk([
        'message'  => 'Child account created successfully',
        'child_id' => $childId,
        'username' => $username,
        'name'     => $displayName,
        'user_id'  => $userId
    ]);
} catch (Throwable $e) {

    $pdo->rollBack();

    jsonError(
        'Could not create child account: ' . $e->getMessage(),
        500
    );
}
