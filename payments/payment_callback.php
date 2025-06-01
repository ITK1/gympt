<?php
require_once '../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || $data['resultCode'] != 0) {
    http_response_code(400);
    exit("Thanh toán không thành công.");
}

$orderInfo = $data['orderInfo'] ?? '';
preg_match('/user (\d+)/', $orderInfo, $matches);
$userId = intval($matches[1] ?? 0);

if (!$userId) {
    exit("Không tìm thấy user.");
}

// Xác định loại giao dịch
$type = str_contains($orderInfo, 'Đăng ký PT') ? 'pt_register' : 'renew';

if ($type === 'pt_register') {
    // Cập nhật trạng thái trainer sang 'pending_payment_done'
    $conn->query("UPDATE trainers SET payment_status = 'done' WHERE user_id = $userId");
} else {
    // Gia hạn tài khoản user
    $conn->query("UPDATE users SET status = 'active' WHERE id = $userId");
}

// Lưu giao dịch nếu cần
$conn->query("INSERT INTO payments (user_id, order_id, amount, status) VALUES ($userId, '{$data['orderId']}', {$data['amount']}, 'success')");

echo "OK";
?>
