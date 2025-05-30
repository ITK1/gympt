<?php
require '../includes/config.php';

// Lấy ID từ GET và kiểm tra hợp lệ
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo "❌ Lỗi: Thiếu hoặc sai ID.";
    exit;
}

// Thực hiện UPDATE bằng prepared statement để an toàn
$stmt = $conn->prepare("UPDATE package_requests SET approved = 1 WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    // ✅ Sau khi duyệt có thể gọi API tạo mã QR Momo tại đây

    // Quay về trang danh sách admin
    header("Location: ./admin/admin_package_requests.php");
    exit;
} else {
    echo "❌ Lỗi khi duyệt yêu cầu: " . $conn->error;
}
?>
