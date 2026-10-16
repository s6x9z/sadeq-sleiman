<?php
require_once __DIR__ . '/../config.php';
$user = requireAuth('provider');
$stmt = db()->prepare("SELECT id, file_name, subject, status, uploaded_at FROM provider_uploads WHERE provider_id=? ORDER BY uploaded_at DESC");
$stmt->execute([$user['id']]);
jsonOk(['uploads' => $stmt->fetchAll()]);
