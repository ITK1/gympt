<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['member', 'admin'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['schedule_id'], $_GET['amount'])) {
    die("Thiếu thông tin thanh toán.");
}

$user_id = $_SESSION['user_id'];
$schedule_id = intval($_GET['schedule_id']);
$amount = floatval($_GET['amount']);

// Kiểm tra trạng thái đã thanh toán chưa
$stmt = $conn->prepare("SELECT payment_status FROM schedules WHERE id = ? AND member_id = ?");
$stmt->bind_param("ii", $schedule_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Không tìm thấy lịch hoặc bạn không có quyền.");
}
$data = $result->fetch_assoc();
if ($data['payment_status'] === 'paid') {
    die("Lịch này đã thanh toán rồi.");
}

// Tạo mã QR thanh toán (giả lập)
$qr_text = "Thanh toán PT Gym\nLịch: $schedule_id\nSố tiền: $amount VND";
$qr_url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($qr_text);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thanh toán lịch tập</title>
</head>
<body>
<h2>Thanh toán lịch tập</h2>
<p>Số tiền: <strong><?= number_format($amount, 0, ',', '.') ?> VND</strong></p>
<img src="<?= $qr_url ?>" alt="QR Code" style="width:300px;">
<p><em>Sau khi thanh toán, trạng thái sẽ được cập nhật tự động hoặc do admin xác nhận.</em></p>
</body>
</html>
