<?php

require_once __DIR__ . '/../config.php';

$parent = requireAuth('parent');

$sql = "
SELECT
    r.id,
    c.title AS course_title
FROM parent_chat_room_members m
JOIN parent_chat_rooms r
    ON m.room_id = r.id
JOIN courses c
    ON r.course_id = c.id
WHERE m.parent_id = ?
ORDER BY c.title
";

$stmt = db()->prepare($sql);
$stmt->execute([$parent['id']]);

jsonOk([
    'rooms' => $stmt->fetchAll(PDO::FETCH_ASSOC)
]);