<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Chỉ cho phép member và admin
$allowed_roles = ['member', 'admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    die("Bạn không có quyền truy cập trang này.");
}

if (!isset($_GET['schedule_id'], $_GET['amount'])) {
    die("Thiếu thông tin thanh toán.");
}

$schedule_id = intval($_GET['schedule_id']);
$amount = floatval($_GET['amount']);

if ($schedule_id <= 0 || $amount <= 0) {
    die("Thông tin thanh toán không hợp lệ.");
}

$user_id = $_SESSION['user_id'];

// Kiểm tra thông tin thanh toán tồn tại
$stmt = $conn->prepare("SELECT p.payment_status FROM payments p WHERE p.schedule_id = ? AND p.user_id = ?");
$stmt->bind_param("ii", $schedule_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Không tìm thấy giao dịch thanh toán.");
}
$payment = $result->fetch_assoc();

if ($payment['payment_status'] === 'paid') {
    die("Lịch đã được thanh toán.");
}

// Lấy thông tin lịch tập để hiển thị
$schedule_stmt = $conn->prepare("
    SELECT s.schedule_time, t.name AS trainer_name
    FROM schedules s
    JOIN trainers t ON s.trainer_id = t.id
    WHERE s.id = ?
");
$schedule_stmt->bind_param("i", $schedule_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();
if ($schedule_result->num_rows === 0) {
    die("Không tìm thấy thông tin lịch tập.");
}
$schedule = $schedule_result->fetch_assoc();

$schedule_time = date('H:i d/m/Y', strtotime($schedule['schedule_time']));
$trainer_name = htmlspecialchars($schedule['trainer_name']);

// Tạo QR code đơn giản
$qr_text = "Thanh toán PT Gym\nLịch: $schedule_id\nSố tiền: $amount VND";
$qr_url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($qr_text);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thanh toán lịch tập</title>
  <link rel="stylesheet" href="/assets/style.css">
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f9f9f9; }
    .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
    h2 { color: #333; }
    p { font-size: 1.1rem; margin: 10px 0; }
    img { margin-top: 20px; }
  </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="container">
  <h2>Thanh toán lịch tập</h2>
  <p><strong>Huấn luyện viên:</strong> <?= $trainer_name ?></p>
  <p><strong>Thời gian:</strong> <?= $schedule_time ?></p>
  <p><strong>Số tiền cần thanh toán:</strong> <?= number_format($amount, 0, ',', '.') ?> VND</p>

  <p>Quét mã QR dưới đây để thanh toán qua ví điện tử:</p>
  <img src="<?= $qr_url ?>" alt="QR code thanh toán" width="300" height="300">

  <p><em>(Sau khi thanh toán, hệ thống sẽ tự động cập nhật trạng thái.)</em></p>
</div>

</body>
</html>
