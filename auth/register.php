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
  <meta charset="UTF-8" />
  <title>Trang cá nhân - PT Gym</title>
  <link rel="stylesheet" href="../assets/style.css" /> <!-- Nếu có CSS chung -->
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
      color: #fff;
      margin-top: 30px;
      border-bottom: 2px solid #ff2e2e;
      padding-bottom: 5px;
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
      border-radius: 5px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      border: 1px solid #444;
      text-align: center;
    }
    th {
      background-color: #ff2e2e;
      color: white;
      font-weight: 600;
    }
    tr:nth-child(even) {
      background-color: #2e2e2e;
    }
    ul {
      list-style: none;
      padding-left: 0;
    }
    ul li {
      padding: 6px 0;
    }
    .status-approved {
      color: lightgreen;
      font-weight: bold;
    }
    .status-pending {
      color: yellow;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Xin chào, <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</h2>

    <?php if ($role === 'admin'): ?>
      <h3>🔧 Quản trị viên</h3>
      <p><a href="manage_accounts.php">👥 Quản lý tài khoản PT & Khách</a></p>
      <p><a href="manage_schedule.php">📅 Quản lý lịch học</a></p>
      <p><a href="admin.php">📂 Duyệt đăng ký gói và PT</a></p>

    <?php elseif ($role === 'pt'): ?>
      <?php
        $stmt = $conn->prepare("SELECT * FROM trainers WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $trainer = $stmt->get_result()->fetch_assoc();
      ?>
      <h3>📋 Thông tin đăng ký PT</h3>
      <a href="../index.php">🏠 Trang Chủ</a>
      <ul>
        <li><strong>Họ tên:</strong> <?= htmlspecialchars($trainer['name']) ?></li>
        <li><strong>Tuổi:</strong> <?= (int)$trainer['age'] ?></li>
        <li><strong>Kinh nghiệm:</strong> <?= nl2br(htmlspecialchars($trainer['experience'])) ?></li>
        <li><strong>Hình thức dạy:</strong> <?= htmlspecialchars($trainer['teach_type']) ?></li>
        <?php if (!empty($trainer['location'])): ?>
          <li><strong>Địa điểm dạy:</strong> <?= htmlspecialchars($trainer['location']) ?></li>
        <?php endif; ?>
        <li><strong>Trạng thái phê duyệt:</strong> 
          <span class="<?= $trainer['approval_status'] === 'approved' ? 'status-approved' : 'status-pending' ?>">
            <?= strtoupper($trainer['approval_status']) ?>
          </span>
        </li>
        <?php if (!empty($trainer['cv_path'])): ?>
          <li><a href="<?= htmlspecialchars($trainer['cv_path']) ?>" target="_blank" rel="noopener">📄 Xem CV</a></li>
        <?php endif; ?>
      </ul>

      <h3>📆 Lịch dạy</h3>
      <?php
        $stmt = $conn->prepare("SELECT schedules.id, members.name AS member_name, date, time 
                                FROM schedules 
                                JOIN members ON schedules.member_id = members.id 
                                WHERE schedules.trainer_id = ? 
                                ORDER BY date, time");
        $stmt->bind_param("i", $trainer['id']);
        $stmt->execute();
        $result = $stmt->get_result();
      ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Thành viên</th>
            <th>Ngày</th>
            <th>Giờ</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['member_name']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['time']) ?></td>
          </tr>
        <?php endwhile; ?>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="4">Chưa có lịch dạy nào.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>

    <?php else: /* member */ ?>
      <a href="../index.php">🏠 Trang Chủ</a>
      <h3>📦 Gói đã đăng ký</h3>
      <?php
        $stmt = $conn->prepare("SELECT package, payment_status FROM members WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $member = $stmt->get_result()->fetch_assoc();
      ?>
      <?php if ($member): ?>
        <p><strong>Gói:</strong> <?= htmlspecialchars($member['package']) ?></p>
        <p><strong>Trạng thái thanh toán:</strong> 
          <?= $member['payment_status'] === 'paid' ? "<span style='color:lightgreen'>ĐÃ THANH TOÁN</span>" : "<span style='color:orange'>CHƯA THANH TOÁN</span>" ?>
        </p>
      <?php else: ?>
        <p>Chưa đăng ký gói tập nào.</p>
      <?php endif; ?>

      <h3>📆 Lịch học</h3>
      <?php
        $stmt = $conn->prepare("SELECT schedules.id, trainers.name AS trainer_name, date, time 
                                FROM schedules 
                                JOIN trainers ON schedules.trainer_id = trainers.id 
                                WHERE schedules.member_id = (SELECT id FROM members WHERE user_id = ?) 
                                ORDER BY date, time");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
      ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Huấn luyện viên</th>
            <th>Ngày</th>
            <th>Giờ</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['trainer_name']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= htmlspecialchars($row['time']) ?></td>
          </tr>
        <?php endwhile; ?>
        <?php if ($result->num_rows === 0): ?>
          <tr><td colspan="4">Chưa có lịch học nào.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</body>
</html>
