<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $experience = $_POST['experience'];
    $teach_type = $_POST['teach_type'];
    $location = $teach_type !== 'online' ? $_POST['location'] : '';
    $cv = '';

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $cv_name = basename($_FILES['cv']['name']);
        $target_dir = "uploads/cv/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $cv_path = $target_dir . uniqid() . "_" . $cv_name;
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
        $cv = $cv_path;
    }

    $stmt = $conn->prepare("INSERT INTO trainers (name, age, experience, teach_type, location, cv_path, approval_status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sissss", $name, $age, $experience, $teach_type, $location, $cv);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Đăng ký thành công. Vui lòng liên hệ admin và chờ duyệt.'); window.location.href='profile.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký PT</title>
</head>
<body>
    <h2>Đăng ký làm Huấn Luyện Viên (PT)</h2>
    <form method="post" enctype="multipart/form-data">
        Họ tên: <input type="text" name="name" required><br>
        Tuổi: <input type="number" name="age" required><br>
        Kinh nghiệm: <textarea name="experience" required></textarea><br>
        Hình thức dạy:
        <select name="teach_type" id="teach_type" onchange="toggleLocation()" required>
            <option value="online">Online</option>
            <option value="offline">Trực tiếp</option>
            <option value="both">Cả hai</option>
        </select><br>
        <div id="location_div" style="display:none;">
            Địa điểm muốn dạy: <input type="text" name="location"><br>
        </div>
        Tải lên CV: <input type="file" name="cv" accept=".pdf,.doc,.docx" required><br><br>
        <strong>Lệ phí đăng ký: 200.000 VNĐ</strong><br>
        Vui lòng liên hệ admin để thanh toán qua Zalo/FB/SĐT:<br>
        📱 Zalo: 0123 456 789<br>
        📘 Facebook: fb.com/adminptgym<br>
        ☎️ SĐT: 0123 456 789<br><br>
        <input type="submit" value="Gửi Đăng Ký">
    </form>

    <script>
        function toggleLocation() {
            var type = document.getElementById('teach_type').value;
            document.getElementById('location_div').style.display = (type !== 'online') ? 'block' : 'none';
        }
    </script>
</body>
</html>
