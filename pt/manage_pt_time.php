<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

// Cơ bản ta tạo bảng pt_times để lưu lịch dạy PT:
// pt_times: id, trainer_id, date, start_time, end_time

// Xử lý thêm thời gian dạy PT mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pt_time'])) {
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $stmt = $conn->prepare("INSERT INTO pt_times (trainer_id, date, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $trainer_id, $date, $start_time, $end_time);
    $stmt->execute();
    header("Location: manage_pt_time.php");
    exit;
}

// Xóa thời gian dạy
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM pt_times WHERE id = $id");
    header("Location: manage_pt_time.php");
    exit;
}

// Lấy danh sách thời gian dạy PT
$result = $conn->query("
    SELECT pt_times.id, trainers.name AS trainer_name, pt_times.date, pt_times.start_time, pt_times.end_time 
    FROM pt_times
    JOIN trainers ON pt_times.trainer_id = trainers.id
    ORDER BY pt_times.date DESC, pt_times.start_time DESC
");

// Lấy danh sách PT để chọn
$trainers = $conn->query("SELECT id, name FROM trainers ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý thời gian dạy PT</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>
<main>
    <h2>Quản lý Thời gian dạy PT</h2>

    <h3>Thêm thời gian dạy PT mới</h3>
    <form method="POST">
        <label>Huấn luyện viên:
            <select name="trainer_id" required>
                <option value="">-- Chọn PT --</option>
                <?php while ($t = $trainers->fetch_assoc()): ?>
                    <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </label><br>
        <label>Ngày: <input type="date" name="date" required></label><br>
        <label>Bắt đầu: <input type="time" name="start_time" required></label><br>
        <label>Kết thúc: <input type="time" name="end_time" required></label><br>
        <button type="submit" name="add_pt_time">Thêm thời gian dạy</button>
    </form>

    <h3>Danh sách thời gian dạy PT</h3>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <tr>
            <th>ID</th><th>Huấn luyện viên</th><th>Ngày</th><th>Bắt đầu</th><th>Kết thúc</th><th>Hành động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['trainer_name']) ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['start_time'] ?></td>
            <td><?= $row['end_time'] ?></td>
            <td>
                <a href="edit_pt_time.php?id=<?= $row['id'] ?>">Sửa</a> | 
                <a href="manage_pt_time.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa thời gian này?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
</body>
</html>
