<?php
require_once './includes/config.php';
require_once 'header.php';

$data = $conn->query("
  SELECT MONTH(payment_date) AS month, SUM(amount) AS total 
  FROM payments 
  GROUP BY month
");

$chart = [];
while ($row = $data->fetch_assoc()) $chart[] = $row;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thống kê Doanh thu - PT GYM</title>
  <link rel="stylesheet" href="assets/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .chart-container {
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2.chart-title {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="chart-container">
    <h2 class="chart-title">Biểu đồ Doanh thu theo Tháng</h2>
    <canvas id="revenueChart" height="100"></canvas>
  </div>

  <script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?= json_encode(array_column($chart, 'month')) ?>,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: <?= json_encode(array_column($chart, 'total')) ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.5)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return value.toLocaleString() + ' đ';
              }
            }
          }
        }
      }
    });
  </script>
</body>
</html>
