<?php
require_once '../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] === 'pt' ? 'pt' : 'member';

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email đã tồn tại!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password_hash', '$role')");
        $user_id = $conn->insert_id;

        if ($role === 'member') {
            $conn->query("INSERT INTO members (name, email, user_id) VALUES ('$name', '$email', $user_id)");
        } else {
            $conn->query("INSERT INTO trainers (name, email, user_id) VALUES ('$name', '$email', $user_id)");
        }

        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        header("Location: profile.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký - PT Gym</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
    }
    .register-container {
      max-width: 450px;
      margin: 80px auto;
      background: rgba(0, 0, 0, 0.85);
      padding: 35px;
      border-radius: 10px;
      color: white;
      box-shadow: 0 0 15px #ff2e2e;
    }
    .register-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #ff2e2e;
    }
    .register-container input,
    .register-container select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 5px;
      font-size: 16px;
    }
    .register-container button {
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
    .register-container button:hover {
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
  <div class="register-container">
    <h2>ĐĂNG KÝ TÀI KHOẢN</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Họ và tên" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <select name="role">
        <option value="member">Thành viên</option>
        <option value="pt">Huấn luyện viên</option>
      </select>
      <button type="submit">Đăng ký</button>
    </form>
    <?php if (!empty($error)) echo "<p class='error-msg'>$error</p>"; ?>
  </div>
</body>
</html>
