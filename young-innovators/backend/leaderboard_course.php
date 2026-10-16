<?php
// ============================================================
// api/leaderboard_course.php
// GET ?id=COURSE_ID → HTML table, fastest 100% completions
// ============================================================
require_once __DIR__ . '/../config.php';

$courseId = (int)get('id');
if (!$courseId) htmlOk('<p>course id required</p>');

$pdo  = db();
$stmt = $pdo->prepare("
    SELECT
        u.display_name,
        ca.accuracy,
        ca.time_ms,
        ca.completed_at
    FROM course_attempts ca
    JOIN students s ON s.user_id = ca.student_id
    JOIN users    u ON u.id      = s.user_id
    WHERE ca.course_id = ? AND ca.accuracy = 100
    ORDER BY ca.time_ms ASC
    LIMIT 10
");
$stmt->execute([$courseId]);
$rows = $stmt->fetchAll();

if (!$rows) {
    htmlOk('<p style="color:var(--text-secondary)">No perfect completions yet. Be the first! 🎯</p>');
}

$html = '<table class="data-table leaderboard-table">'
      . '<thead><tr><th>#</th><th>Student</th><th>Accuracy</th><th>Time</th><th>Date</th></tr></thead><tbody>';

foreach ($rows as $i => $r) {
    $medal = match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => '#' . ($i + 1) };
    $secs  = round($r['time_ms'] / 1000, 1);
    $html .= "<tr>
        <td>{$medal}</td>
        <td><strong>" . htmlspecialchars($r['display_name']) . "</strong></td>
        <td>100%</td>
        <td>{$secs}s</td>
        <td>" . date('M j, Y', strtotime($r['completed_at'])) . "</td>
    </tr>";
}
$html .= '</tbody></table>';

htmlOk($html);
