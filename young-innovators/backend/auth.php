<?php
require_once __DIR__ . '/../config.php';

$action = post('action') ?: get('action');

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'register_parent':
        handleRegisterParent();
        break;
    case 'register_provider':
        handleRegisterProvider();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'me':
        handleMe();
        break;
    default:
        jsonError('Unknown action');
}

function safeUser(array $user): array
{
    return [
        'id' => (int)$user['id'],
        'role' => $user['role'],
        'provider_status' => $user['provider_status'] ?? 'pending',
        'display_name' => $user['display_name'],
        'first_name' => $user['first_name'],
        'last_name' => $user['last_name'],
        'email' => $user['email'],
        'username' => $user['username'] ?? null,
    ];
}

function handleLogin(): void
{
    $identifier = post('identifier');
    $password = post('password');
    $role = post('role');
    if (!$identifier || !$password) jsonError('Missing credentials');

    $pdo = db();
    if ($role === 'student') {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role='student' AND (username=? OR display_name=?) LIMIT 1");
        $stmt->execute([$identifier, $identifier]);
    } else {
        $stmt = $pdo->prepare("
        SELECT * FROM users
        WHERE role IN ('parent','provider','admin')
        AND (email=? OR username=?)
        LIMIT 1
    ");
        $stmt->execute([$identifier, $identifier]);
    }
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) jsonError('Invalid credentials', 401);

    // Providers may log in while pending; provider dashboard displays status and blocks tools.
    startSession();
    $_SESSION['user'] = safeUser($user);
    if ($user['role'] === 'student') updateStreak((int)$user['id'], $pdo);
    jsonOk(['user' => $_SESSION['user'], 'redirect' => dashboardUrl($user['role'])]);
}

function dashboardUrl(string $role): string
{
    return match ($role) {
        'parent' => '../frontend/parent-dashboard.html',
        'student' => '../frontend/student-dashboard.html',
        'provider' => '../frontend/provider-dashboard.html',
        'admin' => '../frontend/admin-dashboard.html',
        default => '../frontend/login.html',
    };
}

function updateStreak(int $userId, PDO $pdo): void
{
    $stmt = $pdo->prepare("SELECT daily_streak, last_login FROM students WHERE user_id=?");
    $stmt->execute([$userId]);
    $s = $stmt->fetch();
    if (!$s) return;
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $streak = (int)$s['daily_streak'];
    if ($s['last_login'] === $yesterday) $streak++;
    elseif ($s['last_login'] !== $today) $streak = 1;
    $pdo->prepare("UPDATE students SET daily_streak=?, last_login=? WHERE user_id=?")->execute([$streak, $today, $userId]);
}

function handleRegisterParent(): void
{
    $email = strtolower(post('email'));
    $password = post('password');
    $username = strtolower(post('username') ?: post('display_name'));
    $display = post('display_name') ?: $username;
    $first = post('first_name');
    $last = post('last_name');
    $phone = post('phone');
    $birth = post('birthdate');
    if (!$email || !$password || !$username || !$display) jsonError('Missing required fields');
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password)
    ) {
        jsonError('Password must contain at least 8 characters, 1 capital letter, 1 number and 1 symbol');
    }
    if ($birth) {
        $age = (new DateTime())->diff(new DateTime($birth))->y;
        if ($age < 18) jsonError('Parent must be at least 18 years old');
    }
    $pdo = db();
    $exists = $pdo->prepare("SELECT id FROM users WHERE email=? OR username=? LIMIT 1");
    $exists->execute([$email, $username]);
    if ($exists->fetch()) jsonError('Email or username already taken');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO users (email, username, password_hash, role, display_name, first_name, last_name, phone, birthdate)
        VALUES (?, ?, ?, 'parent', ?, ?, ?, ?, ?)")->execute([$email, $username, $hash, $display, $first, $last, $phone, $birth ?: null]);
    $uid = (int)$pdo->lastInsertId();
    $pdo->prepare("INSERT INTO parents (user_id) VALUES (?)")->execute([$uid]);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$uid]);
    startSession();
    $_SESSION['user'] = safeUser($stmt->fetch());
    jsonOk(['user' => $_SESSION['user'], 'redirect' => dashboardUrl('parent')]);
}

function handleRegisterProvider(): void
{
    $email = strtolower(post('email'));
    $password = post('password');
    $username = strtolower(post('username') ?: post('display_name'));
    $display = post('display_name') ?: $username;
    $first = post('first_name');
    $last = post('last_name');
    $phone = post('phone');
    $org = post('org');
    $quals = post('qualifications');
    $exp = (int)post('experience');
    $specs = post('specialties');
    $port = post('portfolio_url');
    $bio = post('bio');
    $terms = post('terms');

    if (!$email || !$password || !$username || !$display || !$first || !$last || !$org || !$quals || !$exp || !$specs) {
        jsonError('Please complete all required provider application fields');
    }
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password)
    ) {
        jsonError('Password must contain at least 8 characters, 1 capital letter, 1 number and 1 symbol');
    }
    if (!$port) jsonError('Portfolio or LinkedIn URL is required');
    if ($terms !== '1') jsonError('You must agree to the Provider Guidelines');
    if (empty($_FILES['cv']['tmp_name'])) jsonError('CV upload is required');

    $pdo = db();
    $exists = $pdo->prepare("SELECT id FROM users WHERE email=? OR username=? LIMIT 1");
    $exists->execute([$email, $username]);
    if ($exists->fetch()) jsonError('Email or username already taken');

    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['pdf', 'doc', 'docx'], true)) jsonError('CV must be PDF or Word document');
    if ($_FILES['cv']['size'] > MAX_CV_SIZE) jsonError('CV file too large');
    $fname = 'cv_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($_FILES['cv']['tmp_name'], UPLOAD_DIR . $fname)) jsonError('Could not save CV');
    $cvPath = UPLOAD_URL . $fname;

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $uid = null;
    $pdo->beginTransaction();
    try {
        $pdo->prepare("INSERT INTO users (email, username, password_hash, role, display_name, first_name, last_name, phone)
            VALUES (?, ?, ?, 'provider', ?, ?, ?, ?)")->execute([$email, $username, $hash, $display, $first, $last, $phone]);
        $uid = (int)$pdo->lastInsertId();
        $pdo->prepare("INSERT INTO providers (user_id, status, bio, cv_path, org, qualifications, experience_years, portfolio_url)
            VALUES (?, 'pending', ?, ?, ?, ?, ?, ?)")->execute([$uid, $bio, $cvPath, $org, $quals, $exp, $port]);
        $stmt = $pdo->prepare("INSERT INTO provider_specialties (provider_id, specialty) VALUES (?, ?)");
        foreach (explode(',', $specs) as $sp) {
            $sp = trim($sp);
            if ($sp) $stmt->execute([$uid, $sp]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        jsonError($e->getMessage());
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$uid]);
    startSession();
    $_SESSION['user'] = safeUser($stmt->fetch());
    jsonOk(['user' => $_SESSION['user'], 'message' => 'Application submitted. Awaiting admin approval.', 'redirect' => dashboardUrl('provider')]);
}

function handleLogout(): void
{
    startSession();
    $_SESSION = [];
    session_destroy();
    jsonOk(['redirect' => '../frontend/index.html']);
}

function handleMe(): void
{
    $user = currentUser();
    if (!$user) jsonError('Not logged in', 401);
    jsonOk(['user' => $user]);
}
