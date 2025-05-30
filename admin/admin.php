<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Thống kê
$members = $conn->query("SELECT COUNT(*) AS total FROM members")->fetch_assoc();
$trainers = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc();

// Tổng doanh thu đã thanh toán (trong bảng payments)
$payments = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc();

// Tổng tiền chưa thanh toán trong bảng booking_requests (chưa approved)
$unpaid_booking = $conn->query("SELECT SUM(payment_amount) AS total FROM booking_requests WHERE status != 'approved'")->fetch_assoc();

// Tổng tiền chưa thanh toán trong bảng package_requests (payment_status = pending)
$unpaid_package = $conn->query("SELECT SUM(amount) AS total FROM package_requests WHERE payment_status = 'pending'")->fetch_assoc();

// Tổng tiền chưa thanh toán từ 2 bảng
$total_unpaid = ($unpaid_booking['total'] ?? 0) + ($unpaid_package['total'] ?? 0);

// 5 lịch tập gần nhất
$schedules = $conn->query("
    SELECT schedules.id, members.name AS member_name, trainers.name AS trainer_name, schedules.date, schedules.time 
    FROM schedules 
    JOIN members ON schedules.member_id = members.id 
    JOIN trainers ON schedules.trainer_id = trainers.id
    ORDER BY schedules.date DESC, schedules.time DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản trị - PT GYM</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    main {
      max-width: 1100px;
      margin: 50px auto;
      padding: 20px;
    }
    h2 {
      text-align: center;
      color: #333;
      font-size: 2rem;
    }
    h3 {
      color: #444;
      margin-top: 40px;
      margin-bottom: 15px;
    }
    .stats {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 20px;
    }
    .stat-box {
      flex: 1;
      min-width: 250px;
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    .stat-box strong {
      font-size: 1.5rem;
      color: #ff4c60;
      display: block;
      margin-bottom: 5px;
    }
    ul.functions {
      list-style: none;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    ul.functions li {
      flex: 1 1 45%;
      background: white;
      padding: 15px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    ul.functions a {
      text-decoration: none;
      font-weight: 600;
      color: #333;
      display: block;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
    }
    th {
      background-color: #ff4c60;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>
<?php include '../header.php'; ?>

<main>
  <h2>Chào mừng, <?= htmlspecialchars($name) ?> 👋</h2>

  <h3>Thống kê hệ thống</h3>
  <div class="stats">
    <div class="stat-box">
      <strong><?= $members['total'] ?? 0 ?></strong>
      Thành viên
    </div>
    <div class="stat-box">
      <strong><?= $trainers['total'] ?? 0 ?></strong>
      Huấn luyện viên
    </div>
    <div class="stat-box">
      <strong><?= number_format($payments['total'] ?? 0, 0, ',', '.') ?> VND</strong>
      Doanh thu đã thanh toán
    </div>
    <div class="stat-box">
      <strong><?= number_format($total_unpaid, 0, ',', '.') ?> VND</strong>
      Tiền chưa thanh toán
    </div>
  </div>

  <h3>Chức năng quản lý</h3>
  <ul class="functions">
    <li><a href="../members/members.php">👤 Quản lý Thành viên</a></li>
    <li><a href="/manage_accounts.php">🔐 Quản lý Tài khoản</a></li>
    <li><a href="/manage_schedule.php">📅 Quản lý Lịch tập</a></li>
    <li><a href="/manage_pt_time.php">🕒 Thời gian làm việc của PT</a></li>
    <li><a href="../packages/packages.php">📦 Quản lý Gói tập</a></li>
    <li><a href="../payments/payments.php">💰 Quản lý Thanh toán</a></li>
    <li><a href="/approve_pt.php">📝 Duyệt hồ sơ PT</a></li>
    <li><a href="../admin_pt_approval.php">✅ Duyệt gói tập khách</a></li>
  </ul>

  <h3>📌 5 lịch tập gần nhất</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Thành viên</th>
      <th>Huấn luyện viên</th>
      <th>Ngày</th>
      <th>Giờ</th>
    </tr>
    <?php while ($row = $schedules->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['member_name']) ?></td>
      <td><?= htmlspecialchars($row['trainer_name']) ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= $row['time'] ?></td>
    </tr>
    <?php endwhile; ?>
  </table>
</main>

</body>
</html>
