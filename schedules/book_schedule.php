<?php
// Thiết lập cookie session chuẩn trước khi session_start()
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

require_once '../includes/config.php';

// Kiểm tra đăng nhập và phân quyền
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$trainers = $conn->query("SELECT id, name FROM trainers");

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = intval($_POST['trainer_id'] ?? 0);
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $amount = 500000;

    if ($trainer_id > 0 && $date && $time) {
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time, status) VALUES (?, ?, ?, ?, 'pending')");
            if (!$stmt) throw new Exception("Lỗi prepare schedules: " . $conn->error);
            $stmt->bind_param("iiss", $user_id, $trainer_id, $date, $time);
            $stmt->execute();
            $schedule_id = $stmt->insert_id;
            $stmt->close();

            $stmt2 = $conn->prepare("INSERT INTO payments (user_id, schedule_id, amount, payment_status) VALUES (?, ?, ?, 'pending')");
            if (!$stmt2) throw new Exception("Lỗi prepare payments: " . $conn->error);
            $stmt2->bind_param("iid", $user_id, $schedule_id, $amount);
            $stmt2->execute();
            $stmt2->close();

            $conn->commit();

            header("Location: payment.php?schedule_id=$schedule_id&amount=$amount");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Lỗi khi đặt lịch: " . $e->getMessage();
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt lịch tập</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
    body {
        background-color: #121212;
        color: white;
        font-family: 'Segoe UI', sans-serif;
        padding: 40px 20px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #ff3b3f;
    }

    form {
        max-width: 500px;
        margin: auto;
        background-color: #1e1e1e;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(255, 59, 63, 0.1);
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }

    select,
    input[type="date"],
    input[type="time"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #333;
        background-color: #2a2a2a;
        color: white;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    button {
        width: 100%;
        background-color: #ff3b3f;
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background-color: #e0272a;
    }

    .error {
        color: #ff7777;
        text-align: center;
        margin-bottom: 20px;
    }

    @media (max-width: 600px) {
        form {
            padding: 20px;
        }
    }
    </style>
</head>

<body>
    <?php include '../header.php'; ?>

    <h2>📅 Đặt lịch tập cùng HLV</h2>

    <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <label for="trainer_id">Chọn huấn luyện viên:</label>
        <select name="trainer_id" id="trainer_id" required>
            <option value="">-- Chọn PT --</option>
            <?php 
            if ($trainers && $trainers->num_rows > 0) {
                while ($row = $trainers->fetch_assoc()):
            ?>
            <option value="<?= (int)$row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php 
                endwhile;
            } else {
                echo "<option value=''>Không có huấn luyện viên</option>";
            }
            ?>
        </select>

        <label for="date">Ngày:</label>
        <input type="date" name="date" id="date" required min="<?= date('Y-m-d') ?>">

        <label for="time">Giờ:</label>
        <input type="time" name="time" id="time" required>

        <button type="submit">💪 Đặt lịch ngay</button>
    </form>
</body>

</html>