<?php
// ============================================================
// api/pending_providers.php
// GET → admin sees pending provider applications
// ============================================================
require_once __DIR__ . '/../config.php';

requireAuth('admin');

$pdo  = db();
$stmt = $pdo->prepare("
    SELECT
        u.id, u.display_name, u.first_name, u.last_name, u.email, u.created_at,
        p.status, p.bio, p.cv_path, p.org, p.qualifications, p.experience_years,
        p.portfolio_url, p.rejection_reason, p.applied_at,
        GROUP_CONCAT(ps.specialty ORDER BY ps.specialty SEPARATOR ', ') AS specialties
    FROM providers p
    JOIN users u ON u.id = p.user_id
    LEFT JOIN provider_specialties ps ON ps.provider_id = p.user_id
    WHERE p.status = 'pending'
    GROUP BY p.user_id
    ORDER BY p.applied_at DESC
");
$stmt->execute();
$rows = $stmt->fetchAll();

jsonOk(['providers' => $rows]);
