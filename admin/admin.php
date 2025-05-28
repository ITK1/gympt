<?php
require_once '../includes/config.php';
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user || $user['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Thống kê
$members = $conn->query("SELECT COUNT(*) AS total FROM members")->fetch_assoc();
$trainers = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc();
$payments = $conn->query("SELECT SUM(amount) AS total FROM payments")->fetch_assoc();

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
  <title>Trang quản trị</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>

<main>
  <h2>Xin chào, <?= htmlspecialchars($user['name']) ?>!</h2>
  <h3>Thống kê hệ thống</h3>
  <ul>
    <li><strong>Tổng số thành viên:</strong> <?= $members['total'] ?? 0 ?></li>
    <li><strong>Tổng số huấn luyện viên:</strong> <?= $trainers['total'] ?? 0 ?></li>
    <li><strong>Tổng doanh thu:</strong> <?= number_format($payments['total'] ?? 0, 0, ',', '.') ?> VND</li>
  </ul>

  <h3>Chức năng quản lý</h3>
  <ul>
    <li><a href="../members/members.php">Quản lý Thành viên</a></li>
    <li><a href="manage_accounts.php">Quản lý Tài khoản</a></li>
    <li><a href="manage_schedule.php">Quản lý Lịch tập</a></li>
    <li><a href="manage_pt_time.php">Thời gian làm việc của PT</a></li>
    <li><a href="packages.php">Quản lý Gói tập</a></li>
    <li><a href="payments.php">Quản lý Thanh toán</a></li>
    <li><a href="payments.php">Quản lý thanh toán</a></li>

  </ul>

  <h3>5 lịch tập gần nhất</h3>
  <table border="1" cellpadding="6" cellspacing="0" width="100%">
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
