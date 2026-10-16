<?php
// ============================================================
// api/friends.php
// POST action=add|accept|list
// GET  action=check&name=X
// ============================================================
require_once __DIR__ . '/../config.php';

$action = post('action') ?: get('action');

switch ($action) {
    case 'add':    addFriend();    break;
    case 'accept': acceptFriend(); break;
    case 'list':   listFriends();  break;
    case 'check':  checkUser();    break;
    default: jsonError('Unknown action');
}

// ── Send friend request ──────────────────────────────────────
function addFriend(): void {
    $user       = requireAuth('student');
    $targetName = post('display_name');
    if (!$targetName) jsonError('display_name required');

    $pdo = db();

    // Find target student
    $t = $pdo->prepare("
        SELECT u.id FROM users u
        JOIN students s ON s.user_id = u.id
        WHERE u.display_name = ? AND u.role = 'student'
    ");
    $t->execute([$targetName]);
    $target = $t->fetch();
    if (!$target) jsonError('Student not found');
    if ($target['id'] === $user['id']) jsonError('Cannot add yourself');

    // Check existing
    $dup = $pdo->prepare("
        SELECT id FROM friends
        WHERE (requester_id=? AND addressee_id=?) OR (requester_id=? AND addressee_id=?)
    ");
    $dup->execute([$user['id'], $target['id'], $target['id'], $user['id']]);
    if ($dup->fetch()) jsonError('Friend request already exists');

    $pdo->prepare("INSERT INTO friends (requester_id, addressee_id) VALUES (?, ?)")
        ->execute([$user['id'], $target['id']]);

    jsonOk(['message' => "Friend request sent to {$targetName}."]);
}

// ── Accept friend request ────────────────────────────────────
function acceptFriend(): void {
    $user      = requireAuth('student');
    $friendId  = (int)post('friend_id'); // friends.id
    if (!$friendId) jsonError('friend_id required');

    $pdo = db();
    $chk = $pdo->prepare("SELECT id FROM friends WHERE id=? AND addressee_id=? AND status='pending'");
    $chk->execute([$friendId, $user['id']]);
    if (!$chk->fetch()) jsonError('Request not found');

    $pdo->prepare("UPDATE friends SET status='accepted' WHERE id=?")->execute([$friendId]);
    jsonOk(['message' => 'Friend request accepted!']);
}

// ── List friends ─────────────────────────────────────────────
function listFriends(): void {
    $user = requireAuth('student');
    $pdo  = db();
    $stmt = $pdo->prepare("
        SELECT f.id, f.status,
               u.display_name,
               CASE WHEN f.requester_id = ? THEN 'sent' ELSE 'received' END AS direction
        FROM friends f
        JOIN users u ON u.id = CASE WHEN f.requester_id = ? THEN f.addressee_id ELSE f.requester_id END
        WHERE (f.requester_id = ? OR f.addressee_id = ?)
        ORDER BY f.created_at DESC
    ");
    $stmt->execute([$user['id'], $user['id'], $user['id'], $user['id']]);
    jsonOk(['friends' => $stmt->fetchAll()]);
}

// ── Check if display_name exists (AJAX) ──────────────────────
function checkUser(): void {
    $name = get('name');
    if (!$name) htmlOk('invalid');
    $pdo  = db();
    $stmt = $pdo->prepare("SELECT id FROM users WHERE display_name=? AND role='student'");
    $stmt->execute([$name]);
    htmlOk($stmt->fetch() ? 'found' : 'not_found');
}
