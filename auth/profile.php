<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang cá nhân - PT Gym</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1000px;
      margin: 50px auto;
      background-color: rgba(0,0,0,0.85);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px #ff2e2e;
    }
    h2 {
      color: #ff2e2e;
      text-align: center;
    }
    h3 {
      color: #ffffff;
      margin-top: 30px;
    }
    a {
      color: #00ffff;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #1a1a1a;
      color: white;
    }
    th, td {
      padding: 12px;
      border: 1px solid #444;
      text-align: center;
    }
    th {
      background-color: #ff2e2e;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #2e2e2e;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Xin chào, <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</h2>

    <?php if ($role === 'admin'): ?>
      <h3>Quản lý hệ thống</h3>
      <p><a href="manage_schedule.php">📅 Quản lý lịch tập</a></p>
      <p><a href="manage_users.php">👤 Quản lý tài khoản</a></p>

    <?php elseif ($role === 'pt'): ?>
      <h3>Lịch dạy của bạn</h3>
      <a href="chat_with_pt.php?pt_id=123">Chat với PT này</a>

      <?php
        $stmt = $conn->prepare("SELECT schedules.id, members.name AS member_name, date, time FROM schedules JOIN members ON schedules.member_id = members.id WHERE schedules.trainer_id = (SELECT id FROM trainers WHERE user_id=?) ORDER BY date, time");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
      ?>
      <table>
        <tr><th>ID</th><th>Thành viên</th><th>Ngày</th><th>Giờ</th></tr>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['member_name']) ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['time'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>

    <?php else: ?>
      <a href="../index.php"> Trang Chủ</a>
      <h3>Lịch tập của bạn</h3>
      <?php
        $stmt = $conn->prepare("SELECT schedules.id, trainers.name AS trainer_name, date, time FROM schedules JOIN trainers ON schedules.trainer_id = trainers.id WHERE schedules.member_id = (SELECT id FROM members WHERE user_id=?) ORDER BY date, time");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
      ?>
      <table>
        <tr><th>ID</th><th>Huấn luyện viên</th><th>Ngày</th><th>Giờ</th></tr>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['trainer_name']) ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['time'] ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
