<?php
require_once '../includes/config.php';

// Lấy và kiểm tra dữ liệu GET
$id = intval($_GET['id'] ?? 0);
$email = $_GET['email'] ?? '';

if ($id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Thiếu hoặc sai thông tin ID/email.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}

if ($id && $email) {
    // Lấy thông tin yêu cầu
    $stmt = $conn->prepare("SELECT sr.*, t.name as trainer_name FROM session_requests sr JOIN trainers t ON sr.trainer_id = t.id WHERE sr.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        // Cập nhật trạng thái
        $conn->query("UPDATE session_requests SET status = 'approved' WHERE id = $id");

        // Gửi email
        $to = $email;
        $subject = "Xác nhận buổi tập với PT";
        $message = "Chào {$result['full_name']},\n\n"
                 . "Yêu cầu buổi tập của bạn đã được duyệt:\n"
                 . "- PT: {$result['trainer_name']}\n"
                 . "- Thời gian: {$result['datetime']}\n"
                 . "- Phương thức: {$result['method']}\n"
                 . "- Giá: " . number_format($result['price'], 0, ',', '.') . " VNĐ\n\n"
                 . "Cảm ơn bạn đã đăng ký!";
        $headers = "From: gym@example.com";

        mail($to, $subject, $message, $headers);

        echo "Đã duyệt và gửi email xác nhận.";
    }
}
?>
    <a href="/packages/packages.php"><- quay lai</a>
