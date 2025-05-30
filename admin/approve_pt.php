<?php
require '../includes/config.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("ID không hợp lệ");
}

$stmt = $conn->prepare("UPDATE trainers SET approval_status = 'approved' WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "✅ Đã duyệt thành công.";
    echo "<br><a href='admin_pt_approval.php'>Quay lại danh sách</a>";
} else {
    echo "❌ Lỗi duyệt: " . $conn->error;
}
?>
