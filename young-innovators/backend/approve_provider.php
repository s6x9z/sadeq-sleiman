<?php
// ============================================================
// api/approve_provider.php  POST provider_id=X
// ============================================================
require_once __DIR__ . '/../config.php';
requireAuth('admin');

$providerId = (int)post('provider_id');
if (!$providerId) jsonError('provider_id required');

$pdo = db();
$pdo->prepare("
UPDATE providers
SET status='approved',
    rejection_reason=NULL
WHERE user_id=?
")->execute([$providerId]);

jsonOk([
    'message' => 'Provider approved.'
]);

