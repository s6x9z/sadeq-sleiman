<?php
// ============================================================
// api/reject_provider.php  POST provider_id=X reason=Y
// ============================================================
require_once __DIR__ . '/../config.php';
requireAuth('admin');

$providerId = (int)post('provider_id');
$reason     = post('reason');
if (!$providerId) jsonError('provider_id required');

db()->prepare("UPDATE providers SET status='rejected', rejection_reason=? WHERE user_id=?")
    ->execute([$reason, $providerId]);

jsonOk(['message' => 'Provider rejected.']);
