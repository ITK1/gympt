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

$schedules = $conn->query("
    SELECT s.id, s.date, s.time, m.name AS member_name, t.name AS trainer_name
    FROM schedules s
    LEFT JOIN members m ON s.member_id = m.id
    LEFT JOIN trainers t ON s.trainer_id = t.id
    ORDER BY s.date ASC, s.time ASC
");

$members = $conn->query("SELECT id, name FROM members");
$trainers = $conn->query("SELECT id, name FROM trainers");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Lịch tập - PT Gym</title>
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f4f4f4;
      margin: 0; padding: 0;
    }
    main {
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    h2, h3 {
      text-align: center;
      color: #333;
    }
    form {
      display: grid;
      gap: 15px;
      margin: 20px 0;
    }
    label {
      font-weight: 600;
    }
    input, select, button {
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-family: 'Poppins', sans-serif;
    }
    button {
      background: #ff4c60;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #e03e53;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }
    th {
      background: #f0f0f0;
    }
    a.delete-link {
      color: red;
      text-decoration: none;
    }
  </style>
</head>
<body>
<?php include '../header.php'; ?>
<main>
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
  <table>
    <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>Huấn luyện viên</th>
      <th>Ngày</th>
      <th>Giờ</th>
      <th>Hành động</th>
    </tr>
    <?php while ($row = $schedules->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['member_name']) ?></td>
        <td><?= htmlspecialchars($row['trainer_name']) ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td><a class="delete-link" href="manage_schedule.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xoá lịch này?')">Xoá</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</main>
</body>
</html>