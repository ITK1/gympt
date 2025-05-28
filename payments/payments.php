<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$schedule_id = intval($_GET['schedule_id'] ?? 0);
$amount = floatval($_GET['amount'] ?? 0);

if (!$schedule_id || !$amount) {
    die("Thông tin thanh toán không hợp lệ.");
}

// Kiểm tra lịch và payment hợp lệ
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

// Tạo dữ liệu QR code (giả lập, bạn thay thế gọi API Momo/ZaloPay/VNPay)
$qr_text = "Thanh toán PT Gym\nLịch: $schedule_id\nSố tiền: $amount VND";
$qr_url = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($qr_text);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thanh toán</title>
</head>
<body>
<?php include '../header.php'; ?>

<h2>Thanh toán lịch tập</h2>

<p>Số tiền cần thanh toán: <strong><?= number_format($amount, 0, ',', '.') ?> VND</strong></p>

<p>Quét mã QR dưới đây để thanh toán qua ví điện tử:</p>

<img src="<?= $qr_url ?>" alt="QR code thanh toán" style="width:300px;height:300px;">

<p><em>(Sau khi thanh toán, hệ thống sẽ cập nhật trạng thái tự động.)</em></p>

</body>
</html>
