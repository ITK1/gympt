<?php
require_once '../includes/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] === 'pt' ? 'pt' : 'member'; // Chỉ 2 role đăng ký được

    // Kiểm tra email tồn tại chưa
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email đã tồn tại!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password_hash', '$role')");
        $user_id = $conn->insert_id;

        // Tạo bản ghi trong members hoặc trainers
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

<form method="POST">
    <input type="text" name="name" placeholder="Họ và tên" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Mật khẩu" required><br>
    <select name="role">
        <option value="member">Thành viên</option>
        <option value="pt">Huấn luyện viên</option>
    </select><br>
    <button type="submit">Đăng ký</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
