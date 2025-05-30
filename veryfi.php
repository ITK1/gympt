<?php
require_once '../includes/config.php';

if (!isset($_GET['token'])) {
    die("Liên kết không hợp lệ.");
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT id FROM users WHERE verify_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verify_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $user['id']);
    $stmt->execute();
    echo "✅ Email đã được xác minh thành công!";
} else {
    echo "❌ Token không hợp lệ hoặc đã được sử dụng.";
}
?>
