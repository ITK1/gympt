<?php
require './includes/config.php';

// Nhận dữ liệu từ Momo callback hoặc redirect
$scheduleId = isset($_GET['schedule_id']) ? intval($_GET['schedule_id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($scheduleId <= 0 || !in_array($status, ['success', 'fail'])) {
    die("Dữ liệu thanh toán không hợp lệ.");
}

if ($status === 'success') {
    // Xử lý thanh toán lịch tập
    $stmt = $conn->prepare("SELECT member_id, trainer_id, payment_status FROM schedules WHERE id = ?");
    $stmt->bind_param("i", $scheduleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Không tìm thấy lịch tập.");
    }

    $row = $result->fetch_assoc();
    $trainer_id = $row['trainer_id'];
    $member_id = $row['member_id'];

    if ($row['payment_status'] === 'paid') {
        header("Location: ../auth/profile.php?msg=Lịch tập đã được thanh toán trước đó.");
        exit;
    }

    // Cập nhật trạng thái thanh toán
    $update_stmt = $conn->prepare("UPDATE schedules SET payment_status = 'paid' WHERE id = ?");
    $update_stmt->bind_param("i", $scheduleId);
    $update_stmt->execute();

    // Gửi thông báo cho PT
    $msg = "Học viên đã thanh toán lịch tập.";
    $noti_pt = $conn->prepare("INSERT INTO notifications (user_id, role, message, created_at) VALUES (?, 'pt', ?, NOW())");
    $noti_pt->bind_param("is", $trainer_id, $msg);
    $noti_pt->execute();

    // Gửi thông báo cho khách
    $msg2 = "Bạn đã thanh toán lịch tập thành công.";
    $noti_member = $conn->prepare("INSERT INTO notifications (user_id, role, message, created_at) VALUES (?, 'member', ?, NOW())");
    $noti_member->bind_param("is", $member_id, $msg2);
    $noti_member->execute();
}

header("Location: ../auth/profile.php?msg=Thanh toán lịch tập thành công.");
exit;
?>
