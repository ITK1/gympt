<?php 
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Lấy số liệu thống kê
$members = $conn->query("SELECT COUNT(*) AS total FROM members")->fetch_assoc();
$trainers = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc();
$payments = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc();

// Lấy 5 lịch tập gần nhất để hiển thị nhanh
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
<html>
<head>
  <meta charset="UTF-8">
  <title>Trang quản trị</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>
<main>
  <h2>Trang quản trị</h2>
  <ul>
    <li>Tổng số thành viên: <?= $members['total'] ?></li>
    <li>Tổng số huấn luyện viên: <?= $trainers['total'] ?></li>
    <li>Tổng doanh thu: <?= number_format($payments['total'], 0, ',', '.') ?> VND</li>
  </ul>

  <h3>Chức năng quản lý</h3>
  <ul>
    <li><a href="manage_accounts.php">Quản lý tài khoản</a></li>
    <li><a href="manage_schedule.php">Quản lý lịch tập</a></li>
    <li><a href="manage_pt_time.php">Thời gian dạy của PT</a></li>
    <li><a href="packages.php">Quản lý gói tập</a></li>
    <li><a href="payments.php">Quản lý thanh toán</a></li>
  </ul>

  <h3>5 lịch tập gần nhất</h3>
  <table border="1" cellpadding="6" cellspacing="0">
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
