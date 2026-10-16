<?php
// ============================================================
// api/admin_users.php
// GET → list all users (admin only)
// POST action=delete  user_id=X
// ============================================================
require_once __DIR__ . '/../config.php';
requireAuth('admin');

$action = post('action') ?: get('action');

if ($action === 'delete') {
    $uid = (int)post('user_id');
    if (!$uid) jsonError('user_id required');
    db()->prepare("DELETE FROM users WHERE id=? AND role != 'admin'")->execute([$uid]);
    jsonOk(['message' => 'User deleted.']);
}

// Default: list all users
$pdo  = db();
$stmt = $pdo->prepare("
    SELECT u.id, u.email, u.role, u.display_name, u.first_name, u.last_name, u.created_at,
           p.status AS provider_status
    FROM users u
    LEFT JOIN providers p ON p.user_id = u.id
    ORDER BY u.created_at DESC
");
$stmt->execute();

// Summary counts
$counts = $pdo->query("
    SELECT role, COUNT(*) AS cnt FROM users GROUP BY role
")->fetchAll();
$summary = [];
foreach ($counts as $c) $summary[$c['role']] = $c['cnt'];

jsonOk(['users' => $stmt->fetchAll(), 'summary' => $summary]);
