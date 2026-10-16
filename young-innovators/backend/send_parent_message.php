<?php

require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$roomId = (int)post('room_id');
$message = trim(post('message'));

if (!$roomId || !$message) {
    jsonError('Room ID and message are required');
}

$stmt = db()->prepare("
INSERT INTO parent_chat_messages
(room_id, parent_id, message)
VALUES (?, ?, ?)
");

$stmt->execute([
    $roomId,
    $parent['id'],
    $message
]);

jsonOk([
    'message' => 'Sent'
]);