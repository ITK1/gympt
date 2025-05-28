<?php
require_once './includes/config.php';

// Ghi log truy cập
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$stmt = $conn->prepare("INSERT INTO visitor_logs (ip, user_agent) VALUES (?, ?)");
$stmt->bind_param("ss", $ip, $user_agent);
$stmt->execute();

// Tổng lượt truy cập
$total_visits = $conn->query("SELECT COUNT(*) AS total FROM visitor_logs")->fetch_assoc()['total'];

// Thống kê truy cập theo ngày (7 ngày gần nhất)
$result = $conn->query("SELECT DATE(visit_time) AS visit_date, COUNT(*) AS count FROM visitor_logs GROUP BY visit_date ORDER BY visit_date DESC LIMIT 7");

$visits_per_day = [];
while ($row = $result->fetch_assoc()) {
    $visits_per_day[$row['visit_date']] = $row['count'];
}

// Đảm bảo đủ 7 ngày kể cả ngày không có lượt
for ($i=6; $i>=0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    if (!isset($visits_per_day[$day])) $visits_per_day[$day] = 0;
}
ksort($visits_per_day);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Thống kê truy cập website</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { max-width: 700px; margin: auto; font-family: Arial, sans-serif; padding: 20px; }
        h1 { text-align: center; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

<h1>Thống kê truy cập website</h1>
<p><strong>Tổng lượt truy cập:</strong> <?= $total_visits ?></p>

<canvas id="visitsChart" width="600" height="300"></canvas>

<table>
    <thead>
        <tr><th>Ngày</th><th>Lượt truy cập</th></tr>
    </thead>
    <tbody>
        <?php foreach ($visits_per_day as $date => $count): ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($date)) ?></td>
                <td><?= $count ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
const ctx = document.getElementById('visitsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($visits_per_day)) ?>,
        datasets: [{
            label: 'Lượt truy cập theo ngày',
            data: <?= json_encode(array_values($visits_per_day)) ?>,
            borderColor: 'rgb(75, 192, 192)',
            fill: false,
            tension: 0.2
        }]
    },
    options: {
        scales: {
            x: { 
                title: { display: true, text: 'Ngày' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Số lượt truy cập' }
            }
        }
    }
});
</script>

</body>
</html>
