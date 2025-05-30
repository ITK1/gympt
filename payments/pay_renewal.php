<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ./auth/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['user_role'] ?? null; // Sửa lỗi tại đây

// Kiểm tra trạng thái người dùng
$user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();

if ($user['status'] === 'active') {
    echo "Tài khoản của bạn đang hoạt động, không cần gia hạn.";
    exit;
}

// Giả sử phí gia hạn là 200.000 VNĐ
$amount = 200000;
$momoQR = "https://api.qrserver.com/v1/create-qr-code/?data=MOMO:PTGYM-$userId-$amount&size=200x200";

?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Gia hạn tài khoản</title>
  <style>
    body { font-family: Arial; text-align: center; margin-top: 50px; }
    .card { max-width: 400px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
    .qr { margin: 20px 0; }
    .btn-back { margin-top: 20px; display: inline-block; background: #444; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Gia hạn tài khoản</h2>
    <p>Phí gia hạn: <strong>200.000 VNĐ</strong></p>
    <p>Vui lòng quét mã QR dưới đây để thanh toán:</p>
    <div class="qr">
      <img src="<?= $momoQR ?>" alt="QR Momo">
    </div>
    <p>Sau khi thanh toán, hệ thống sẽ tự động kích hoạt lại tài khoản của bạn.</p>
    <a class="btn-back" href="/auth/profile.php">Quay lại hồ sơ</a>
  </div>
</body>
</html>
