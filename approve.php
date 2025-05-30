<?php
require_once './includes/config.php';

// Lấy và kiểm tra dữ liệu GET
$id = intval($_GET['id'] ?? 0);
$email = $_GET['email'] ?? '';

if ($id <= 0 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Thiếu hoặc sai thông tin ID/email.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}

// Lấy thông tin yêu cầu từ DB
$stmt = $conn->prepare("
    SELECT sr.*, t.name AS trainer_name 
    FROM session_requests sr 
    JOIN trainers t ON sr.trainer_id = t.id 
    WHERE sr.id = ?
");
if (!$stmt) {
    echo "⚠️ Lỗi chuẩn bị câu truy vấn.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if (!$result) {
    echo "❌ Không tìm thấy yêu cầu có ID = $id.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}

// Cập nhật trạng thái "approved"
$updateStmt = $conn->prepare("UPDATE session_requests SET status = 'approved' WHERE id = ?");
if (!$updateStmt) {
    echo "⚠️ Lỗi chuẩn bị câu truy vấn cập nhật.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}
$updateStmt->bind_param("i", $id);
$updateStmt->execute();

if ($updateStmt->affected_rows <= 0) {
    echo "⚠️ Trạng thái đã được cập nhật trước đó hoặc có lỗi xảy ra.";
    echo '<br><a href="packages.php">← Quay lại</a>';
    exit;
}

// Soạn và gửi email
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

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Yêu cầu đã được duyệt và email xác nhận đã được gửi đến $email.";
} else {
    echo "⚠️ Đã duyệt yêu cầu, nhưng gửi email thất bại. Vui lòng liên hệ thủ công.";
}

echo '<br><a href="packages.php">← Quay lại</a>';
?>
