<?php
require_once '../includes/config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'user')");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    header("Location: login.php");
}
?>
<form method="POST">
    Email: <input name="email" required><br>
    Mật khẩu: <input type="password" name="password" required><br>
    <button>Đăng ký</button>
</form>