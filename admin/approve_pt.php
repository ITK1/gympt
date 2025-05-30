<?php
require_once '../includes/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Thiếu hoặc sai ID.");
}

$id = (int)$_GET['id'];

// Cập nhật trạng thái phê duyệt
$stmt = $conn->prepare("UPDATE trainers SET approved = 1 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Lấy user_id của PT để gửi thông báo
$stmt = $conn->prepare("SELECT user_id FROM trainers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $pt_user_id = $row['user_id'];

    // Gửi thông báo
    $msg = "🎉 Hồ sơ của bạn đã được admin duyệt. Bạn có thể nhận lịch huấn luyện.";
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $pt_user_id, $msg);
    $stmt->execute();
}

header("Location: /admin_pt_approval.php");
exit;
?>
