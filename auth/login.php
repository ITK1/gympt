<?php
require_once '../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            header("Location: profile.php");
            exit;
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Email không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập - PT Gym</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
    }
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      background: rgba(0, 0, 0, 0.8);
      padding: 30px;
      border-radius: 10px;
      color: white;
      box-shadow: 0 0 15px red;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #ff2e2e;
    }
    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      font-size: 16px;
    }
    .login-container button {
      width: 100%;
      background-color: #ff2e2e;
      color: white;
      border: none;
      padding: 12px;
      font-size: 18px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }
    .login-container button:hover {
      background-color: #d90000;
    }
    .error-msg {
      color: #ff4d4d;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>ĐĂNG NHẬP PT GYM</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
    </form>
    <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
  </div>
</body>
</html>
