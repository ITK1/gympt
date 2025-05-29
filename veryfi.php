<?php
require './includes/config.php';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id, verified FROM users WHERE verify_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['verified']) {
            echo "Tài khoản đã được xác minh. <a href='login.php'>Đăng nhập</a>";
        } else {
            $update = $conn->prepare("UPDATE users SET verified = 1, verify_token = NULL WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();
            echo "Xác minh thành công! <a href='login.php'>Đăng nhập</a>";
        }
    } else {
        echo "Token không hợp lệ hoặc đã được sử dụng.";
    }
} else {
    echo "Thiếu token xác minh.";
}
?>
