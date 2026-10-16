<?php
// ============================================================
// api/leaderboard_global.php
// GET → returns HTML table, top 20 students
// Score = daily_streak * 10 + avg_improvement
// ============================================================
require_once __DIR__ . '/../config.php';

$pdo  = db();
$stmt = $pdo->prepare("
    SELECT
        u.display_name,
        s.daily_streak,
        COALESCE(AVG(ca.accuracy), 0) AS avg_accuracy,
        COALESCE(SUM(sb.xp), 0)       AS total_xp,
        (s.daily_streak * 10 + COALESCE(AVG(ca.accuracy), 0)) AS score
    FROM students s
    JOIN users u ON u.id = s.user_id
    LEFT JOIN course_attempts ca ON ca.student_id = s.user_id
    LEFT JOIN student_badges  sb ON sb.student_id = s.user_id
    GROUP BY s.user_id
    ORDER BY score DESC
    LIMIT 20
");
$stmt->execute();
$rows = $stmt->fetchAll();

$html = '<table class="data-table leaderboard-table">'
      . '<thead><tr><th>#</th><th>Student</th><th>Streak 🔥</th><th>Avg Accuracy</th><th>Total XP</th><th>Score</th></tr></thead><tbody>';

foreach ($rows as $i => $r) {
    $medal = match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => '#' . ($i + 1) };
    $html .= "<tr>
        <td>{$medal}</td>
        <td><strong>" . htmlspecialchars($r['display_name']) . "</strong></td>
        <td>{$r['daily_streak']} days</td>
        <td>" . round((float)$r['avg_accuracy'], 1) . "%</td>
        <td>" . (int)$r['total_xp'] . " XP</td>
        <td><strong>" . round((float)$r['score'], 1) . "</strong></td>
    </tr>";
}
$html .= '</tbody></table>';

htmlOk($html);
