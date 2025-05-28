<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit("Bạn chưa đăng nhập.");
}

$from_user = $_SESSION['user_id'];
$to_user = intval($_POST['chat_with'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($to_user <= 0 || $message === '') {
    http_response_code(400);
    exit("Thiếu dữ liệu.");
}

// Lưu tin nhắn
$stmt = $conn->prepare("INSERT INTO messages (from_user, to_user, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $from_user, $to_user, $message);
if ($stmt->execute()) {
    echo "success";
} else {
    http_response_code(500);
    echo "Lỗi ghi DB.";
}
