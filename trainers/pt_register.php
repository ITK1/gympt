<?php
include '../includes/config.php';
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
    
header("Location: ../payments/create_momo_qr.php?type=pt_register&user_id=$userId");
exit;

    echo "<script>alert('Đăng ký thành công. Vui lòng liên hệ admin và chờ duyệt.'); window.location.href='profile.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Đăng ký PT - PT Gym</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0; padding: 0;
        }
        .container {
            max-width: 600px;
            background-color: rgba(0,0,0,0.85);
            margin: 50px auto;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px #ff2e2e;
        }
        h2 {
            text-align: center;
            color: #ff2e2e;
            margin-bottom: 30px;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
        }
        form textarea {
            resize: vertical;
            min-height: 80px;
        }
        form input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            background-color: #ff2e2e;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        form input[type="submit"]:hover {
            background-color: #cc2626;
        }
        #location_div {
            margin-top: 10px;
        }
        .fee-info {
            margin-top: 25px;
            font-weight: 700;
            color: #ff2e2e;
            text-align: center;
        }
        .contact-info {
            margin-top: 15px;
            line-height: 1.6;
            font-size: 0.9rem;
            text-align: center;
            color: #ccc;
        }
        .contact-info span {
            display: block;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đăng ký làm Huấn Luyện Viên (PT)</h2>
        <form method="post" enctype="multipart/form-data" novalidate>
            <label for="name">Họ tên:</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Tuổi:</label>
            <input type="number" id="age" name="age" required min="18" max="100">

            <label for="experience">Kinh nghiệm:</label>
            <textarea id="experience" name="experience" required></textarea>

            <label for="teach_type">Hình thức dạy:</label>
            <select name="teach_type" id="teach_type" onchange="toggleLocation()" required>
                <option value="online">Online</option>
                <option value="offline">Trực tiếp</option>
                <option value="both">Cả hai</option>
            </select>

            <div id="location_div" style="display:none;">
                <label for="location">Địa điểm muốn dạy:</label>
                <input type="text" id="location" name="location">
            </div>

            <label for="cv">Tải lên CV:</label>
            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>

            <div class="fee-info">
                <p>Lệ phí đăng ký: 200.000 VNĐ</p>
            </div>

            <div class="contact-info">
                <p>Vui lòng liên hệ admin để thanh toán qua Zalo/FB/SĐT:</p>
                <span>📱 Zalo: 0123 456 789</span>
                <span>📘 Facebook: fb.com/adminptgym</span>
                <span>☎️ SĐT: 0123 456 789</span>
            </div>

            <input type="submit" value="Gửi Đăng Ký">
        </form>
    </div>

    <script>
        function toggleLocation() {
            var type = document.getElementById('teach_type').value;
            document.getElementById('location_div').style.display = (type !== 'online') ? 'block' : 'none';
        }
        // Gọi hàm 1 lần khi trang load để ẩn/hiện đúng
        window.onload = toggleLocation;
    </script>
</body>
</html>
