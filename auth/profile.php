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

<main>
  <h2>Xin chào, <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</h2>

<?php if ($role === 'admin'): ?>
  <h3>Quản lý Huấn luyện viên và Thành viên</h3>
  <a href="manage_schedule.php">Quản lý lịch tập</a><br>
  <a href="manage_users.php">Quản lý tài khoản</a><br>
<?php elseif ($role === 'pt'): ?>
  <h3>Lịch dạy của bạn</h3>
  <?php
  // Lấy lịch dạy PT
  $stmt = $conn->prepare("SELECT schedules.id, members.name AS member_name, date, time FROM schedules JOIN members ON schedules.member_id = members.id WHERE schedules.trainer_id = (SELECT id FROM trainers WHERE user_id=?) ORDER BY date, time");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  ?>
  <table border="1">
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

<?php else: // member ?>
  <h3>Lịch tập của bạn</h3>
  <?php
  $stmt = $conn->prepare("SELECT schedules.id, trainers.name AS trainer_name, date, time FROM schedules JOIN trainers ON schedules.trainer_id = trainers.id WHERE schedules.member_id = (SELECT id FROM members WHERE user_id=?) ORDER BY date, time");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  ?>
  <table border="1">
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
</main>
