<?php
// Thiết lập cookie session chuẩn trước khi session_start()
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require_once '../includes/config.php';

// Kiểm tra đăng nhập và phân quyền
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (strtolower($_SESSION['role']) !== 'member') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách huấn luyện viên
$trainers = $conn->query("SELECT id, name FROM trainers");
if (!$trainers) {
    die("Lỗi truy vấn danh sách huấn luyện viên: " . $conn->error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = isset($_POST['trainer_id']) ? intval($_POST['trainer_id']) : 0;
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';
    $amount = 500000; // Số tiền cố định

    if ($trainer_id > 0 && !empty($date) && !empty($time)) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time, status) VALUES (?, ?, ?, ?, 'pending')");
            if (!$stmt) throw new Exception("Lỗi prepare schedules: " . $conn->error);
            $stmt->bind_param("iiss", $user_id, $trainer_id, $date, $time);
            $stmt->execute();
            $schedule_id = $stmt->insert_id;
            $stmt->close();

            $stmt2 = $conn->prepare("INSERT INTO payments (user_id, schedule_id, amount, payment_status) VALUES (?, ?, ?, 'pending')");
            if (!$stmt2) throw new Exception("Lỗi prepare payments: " . $conn->error);
            $stmt2->bind_param("iid", $user_id, $schedule_id, $amount);
            $stmt2->execute();
            $stmt2->close();

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
  <link rel="stylesheet" href="/assets/style.css" />
</head>
<body>
<?php include '../header.php'; ?>

<h2>Đặt lịch tập</h2>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="">
    <label>Chọn PT:</label>
    <select name="trainer_id" required>
        <option value="">-- Chọn huấn luyện viên --</option>
        <?php 
        if ($trainers->num_rows > 0) {
            while ($row = $trainers->fetch_assoc()): ?>
                <option value="<?= (int)$row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile;
        } else {
            echo "<option value=''>Không có huấn luyện viên</option>";
        }
        ?>
    </select><br><br>

    <label>Ngày:</label>
    <input type="date" name="date" required min="<?= date('Y-m-d') ?>" /><br><br>

    <label>Giờ:</label>
    <input type="time" name="time" required /><br><br>

    <button type="submit">Đặt lịch</button>
</form>

</body>
</html>
