<?php

require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$roomId = (int)($_GET['room_id'] ?? 0);

if (!$roomId) {
    jsonError('Room ID required');
}

$sql = "
SELECT
    m.id,
    m.message,
    m.created_at,
    u.display_name
FROM parent_chat_messages m
JOIN users u
    ON m.parent_id = u.id
WHERE m.room_id = ?
ORDER BY m.created_at ASC
";

$stmt = db()->prepare($sql);
$stmt->execute([$roomId]);

jsonOk([
    'messages' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);