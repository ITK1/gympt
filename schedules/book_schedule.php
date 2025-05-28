<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$trainers = $conn->query("SELECT id, name FROM trainers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $amount = 500000; // ví dụ tiền cố định, bạn thay tùy theo gói

    if ($trainer_id && $date && $time) {
        $conn->begin_transaction();
        try {
            // Thêm lịch với trạng thái pending
            $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->bind_param("iiss", $user_id, $trainer_id, $date, $time);
            $stmt->execute();
            $schedule_id = $stmt->insert_id;

            // Tạo bản ghi payment trạng thái pending
            $stmt2 = $conn->prepare("INSERT INTO payments (user_id, schedule_id, amount, payment_status) VALUES (?, ?, ?, 'pending')");
            $stmt2->bind_param("iid", $user_id, $schedule_id, $amount);
            $stmt2->execute();

            $conn->commit();

            header("Location: payment.php?schedule_id=$schedule_id&amount=$amount");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Lỗi khi đặt lịch: " . $e->getMessage();
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
