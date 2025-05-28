<?php
require_once __DIR__ . '/includes/config.php';  // Đường dẫn đúng đến file config.php của bạn

// Ghi log truy cập
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$stmt = $conn->prepare("INSERT INTO visitor_logs (ip, user_agent) VALUES (?, ?)");
$stmt->bind_param("ss", $ip, $user_agent);
$stmt->execute();
?>
