<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$allowed_roles = ['member', 'admin'];
if (!in_array($_SESSION['role'], $allowed_roles)) {
    die("Bạn không có quyền truy cập trang này.");
}

if (!isset($_GET['schedule_id'], $_GET['amount'])) {
    die("Thiếu thông tin thanh toán.");
}

$schedule_id = intval($_GET['schedule_id']);
$amount = floatval($_GET['amount']);
$user_id = $_SESSION['user_id'];

// Kiểm tra thanh toán tồn tại và chưa thanh toán
$stmt = $conn->prepare("SELECT payment_status FROM payments WHERE schedule_id = ? AND user_id = ?");
$stmt->bind_param("ii", $schedule_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result->num_rows) die("Không tìm thấy giao dịch.");
$row = $result->fetch_assoc();
if ($row['payment_status'] === 'paid') die("Lịch đã được thanh toán.");

// Hiển thị thông tin & nút thanh toán
echo "<h2>Xác nhận thanh toán</h2>";
echo "<p>Số tiền: " . number_format($amount, 0, ',', '.') . " VND</p>";
echo "<a href='momo_qr_schedule.php?schedule_id=$schedule_id&amount=$amount'>Thanh toán với MoMo</a>";
?>
