<?php
require_once '../includes/config.php';
$schedule_id = $_GET['schedule_id'] ?? 0;

if ($schedule_id > 0) {
    $stmt = $conn->prepare("UPDATE payments SET payment_status = 'paid' WHERE schedule_id = ?");
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();
    echo "<h2>Thanh toán thành công!</h2>";
    echo "<p>Lịch tập đã được xác nhận thanh toán.</p>";
} else {
    echo "Không có thông tin lịch.";
}
?>
