<?php

require_once __DIR__ . '/../config.php';

$user = requireAuth('provider');

$userId = (int)$user['id'];

$stmt = db()->prepare("
    SELECT status
    FROM providers
    WHERE user_id = ?
");

$stmt->execute([$userId]);

$status = $stmt->fetchColumn();

jsonOk([
    'status' => $status ?: 'pending'
]);