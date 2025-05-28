<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Xử lý thêm lịch mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $member_id = $_POST['member_id'];
    $trainer_id = $_POST['trainer_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $member_id, $trainer_id, $date, $time);
    $stmt->execute();

    header("Location: manage_schedule.php");
    exit;
}
if (isset($_GET['delete'])) {
    $schedule_id = intval($_GET['delete']);
    $conn->query("DELETE FROM schedules WHERE id = $schedule_id");
    header("Location: manage_schedule.php");
    exit;
}


// Lấy danh sách lịch tập
$schedules = $conn->query("
    SELECT s.id, s.date, s.time, m.name AS member_name, t.name AS trainer_name
    FROM schedules s
    LEFT JOIN members m ON s.member_id = m.id
    LEFT JOIN trainers t ON s.trainer_id = t.id
    ORDER BY s.date ASC, s.time ASC
");

// Lấy danh sách thành viên và PT để chọn
$members = $conn->query("SELECT id, name FROM members");
$trainers = $conn->query("SELECT id, name FROM trainers");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Quản lý Lịch tập</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>
<main>
    <td>
  <a href="manage_schedule.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa lịch này?')">Xoá</a>
</td>

  <h2>Quản lý Lịch tập</h2>

  <h3>Thêm lịch tập mới</h3>
  <form method="POST">
    <label>Khách hàng:</label>
    <select name="member_id" required>
      <?php while ($m = $members->fetch_assoc()): ?>
        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Huấn luyện viên:</label>
    <select name="trainer_id" required>
      <?php while ($t = $trainers->fetch_assoc()): ?>
        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
      <?php endwhile; ?>
    </select>

    <label>Ngày:</label>
    <input type="date" name="date" required>

    <label>Giờ:</label>
    <input type="time" name="time" required>

    <button type="submit" name="add_schedule">Thêm lịch</button>
  </form>

  <h3>Danh sách lịch đã tạo</h3>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>PT</th>
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
