<?php
require_once __DIR__ . '/../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) return;

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'] ?? null;


$stmt = $conn->prepare("SELECT last_payment, status FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $lastPayment = $row['last_payment'];
    $status = $row['status'];
    $now = new DateTime();
    $last = new DateTime($lastPayment ?? '2000-01-01');
    $daysPassed = $now->diff($last)->days;

    if ($daysPassed > 30 && $status !== 'expired') {
        $conn->query("UPDATE users SET status = 'expired' WHERE id = $userId");
    }

    // Nếu đã expired thì hiển thị cảnh báo
    if ($status === 'expired' || $daysPassed > 30) {
        $_SESSION['payment_warning'] = "Tài khoản của bạn đã hết hạn. Vui lòng <a href='/payments/pay_renewal.php'>gia hạn tại đây</a> để tiếp tục sử dụng.";
    } else {
        unset($_SESSION['payment_warning']);
    }
}
