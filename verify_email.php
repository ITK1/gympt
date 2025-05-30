<?php
require_once '../includes/config.php';
require_once '../vendor/autoload.php'; // Nếu dùng Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    die("Vui lòng đăng nhập.");
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$name = $_SESSION['name'];

// Tạo token ngẫu nhiên
$token = bin2hex(random_bytes(32));
$link = "http://yourdomain.com/verify.php?token=$token";

// Lưu token vào database
$stmt = $conn->prepare("UPDATE users SET verify_token = ? WHERE id = ?");
$stmt->bind_param("si", $token, $user_id);
$stmt->execute();

// Gửi email
$mail = new PHPMailer(true);
try {
    // Cấu hình SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Hoặc smtp.mailtrap.io nếu test
    $mail->SMTPAuth = true;
    $mail->Username = 'your_gmail@gmail.com'; // Gmail
    $mail->Password = 'your_app_password';    // App password (không phải mật khẩu Gmail thông thường)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Thiết lập người gửi
    $mail->setFrom('your_gmail@gmail.com', 'PT Gym');
    $mail->addAddress($email, $name);
    $mail->isHTML(true);
    $mail->Subject = 'Xác minh Email - PT Gym';
    $mail->Body = "Chào $name,<br><br>Vui lòng bấm vào link sau để xác minh email của bạn:<br><br>
                   <a href='$link'>Xác minh Email</a><br><br>Xin cảm ơn!";

    $mail->send();
    echo "✅ Đã gửi email xác minh tới $email. Vui lòng kiểm tra hộp thư đến.";
} catch (Exception $e) {
    echo "❌ Lỗi khi gửi email: {$mail->ErrorInfo}";
}
?>
