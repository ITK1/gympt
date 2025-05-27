<?php
require './includes/config.php';
if (isset($_GET['token'])) {
  $stmt = $conn->prepare("UPDATE users SET verified = 1 WHERE verify_token = ?");
  $stmt->bind_param("s", $_GET['token']);
  $stmt->execute();
  echo "Xác minh thành công. <a href='login.php'>Đăng nhập</a>";
}
?>