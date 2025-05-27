<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách PT để chọn
$trainers = $conn->query("SELECT id, name FROM trainers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Kiểm tra dữ liệu đơn giản
    if ($trainer_id && $date && $time) {
        // Chèn vào schedules với trạng thái pending
        $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iiss", $user_id, $trainer_id, $date, $time);
        if ($stmt->execute()) {
            $message = "Đặt lịch thành công. Đang chờ duyệt.";
        } else {
            $error = "Lỗi khi đặt lịch.";
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Đặt lịch tập</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>

<h2>Đặt lịch tập</h2>

<?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <label>Chọn PT:</label>
    <select name="trainer_id" required>
        <option value="">-- Chọn huấn luyện viên --</option>
        <?php while ($row = $trainers->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Ngày:</label>
    <input type="date" name="date" required min="<?= date('Y-m-d') ?>"><br><br>

    <label>Giờ:</label>
    <input type="time" name="time" required><br><br>

    <button type="submit">Đặt lịch</button>
</form>

</body>
</html>
