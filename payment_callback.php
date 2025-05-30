<?php
require './includes/config.php';

// Nhận dữ liệu từ Momo callback hoặc redirect
$requestId = isset($_GET['request_id']) ? intval($_GET['request_id']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : '';

if ($requestId <= 0 || !in_array($status, ['success', 'fail'])) {
    die("Dữ liệu thanh toán không hợp lệ.");
}

if ($status === 'success') {
    // Xử lý thanh toán gói tập
    $stmt = $conn->prepare("SELECT member_id, trainer_id, payment_status FROM package_requests WHERE id = ?");
    $stmt->bind_param("i", $requestId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Không tìm thấy yêu cầu gói tập.");
    }

    $request = $result->fetch_assoc();
    $trainer_id = $request['trainer_id'];
    $member_id = $request['member_id'];

    if ($request['payment_status'] === 'paid') {
        header("Location: ../auth/profile.php?msg=Gói tập đã được thanh toán trước đó.");
        exit;
    }

    // Cập nhật trạng thái thanh toán
    $update_stmt = $conn->prepare("UPDATE package_requests SET payment_status = 'paid' WHERE id = ?");
    $update_stmt->bind_param("i", $requestId);
    $update_stmt->execute();

    // Gửi thông báo cho PT
    $msg = "Bạn có học viên mới vừa thanh toán gói tập.";
    $noti_pt = $conn->prepare("INSERT INTO notifications (user_id, role, message, created_at) VALUES (?, 'pt', ?, NOW())");
    $noti_pt->bind_param("is", $trainer_id, $msg);
    $noti_pt->execute();

    // Gửi thông báo cho khách
    $msg2 = "Bạn đã thanh toán gói tập thành công. Thông tin PT đã được hiển thị.";
    $noti_member = $conn->prepare("INSERT INTO notifications (user_id, role, message, created_at) VALUES (?, 'member', ?, NOW())");
    $noti_member->bind_param("is", $member_id, $msg2);
    $noti_member->execute();
}

header("Location: ../auth/profile.php?msg=Thanh toán thành công.");
exit;
?>
