<?php
require_once '../includes/config.php';

// Ví dụ giả lập nhận POST từ ví điện tử
$schedule_id = intval($_POST['schedule_id'] ?? 0);
$transaction_id = $_POST['transaction_id'] ?? null;
$status = $_POST['status'] ?? null; // ví dụ 'success' hoặc 'failed'

if ($schedule_id && $transaction_id && $status === 'success') {
    // Cập nhật trạng thái thanh toán thành 'paid'
    $stmt = $conn->prepare("UPDATE payments SET payment_status='paid', transaction_id=?, payment_date=NOW() WHERE schedule_id=?");
    $stmt->bind_param("si", $transaction_id, $schedule_id);
    $stmt->execute();

    // Bạn có thể tự động duyệt lịch hoặc để admin duyệt
    // $stmt2 = $conn->prepare("UPDATE schedules SET status='approved' WHERE id=?");
    // $stmt2->bind_param("i", $schedule_id);
    // $stmt2->execute();

    http_response_code(200);
    echo "OK";
} else {
    http_response_code(400);
    echo "Invalid request";
}
