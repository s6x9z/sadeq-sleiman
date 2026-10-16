<?php
// ============================================================
// api/check_display_name.php
// GET ?name=xxx  → returns "available" or "taken"
// ============================================================
require_once __DIR__ . '/../config.php';

$name = get('name');
if (!$name) htmlOk('invalid');

$stmt = db()->prepare("SELECT id FROM users WHERE display_name = ?");
$stmt->execute([$name]);
htmlOk($stmt->fetch() ? 'taken' : 'available');
