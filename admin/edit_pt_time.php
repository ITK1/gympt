<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Lấy ID thời gian dạy cần sửa
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_pt_time.php");
    exit;
}
$time_id = intval($_GET['id']);

// Lấy thông tin hiện tại
$stmt = $conn->prepare("SELECT * FROM pt_times WHERE id = ?");
$stmt->bind_param("i", $time_id);
$stmt->execute();
$time_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$time_data) {
    header("Location: manage_pt_time.php");
    exit;
}

// Lấy danh sách PT
$trainers = $conn->query("SELECT id, name FROM trainers ORDER BY name");

// Xử lý cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_pt_time'])) {
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if ($trainer_id > 0 && $date && $start_time && $end_time) {
        $stmt = $conn->prepare("UPDATE pt_times SET trainer_id = ?, date = ?, start_time = ?, end_time = ? WHERE id = ?");
        $stmt->bind_param("isssi", $trainer_id, $date, $start_time, $end_time, $time_id);
        if ($stmt->execute()) {
            header("Location: manage_pt_time.php?msg=updated");
            exit;
        } else {
            $error = "Lỗi cập nhật thời gian dạy: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thời gian dạy PT</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            margin: 0;
            padding: 0;
        }
        main {
            max-width: 600px;
            margin: 30px auto;
            background: #222;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ff4444;
        }
        h2 {
            color: #ff5555;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            padding: 8px;
            width: 100%;
            max-width: 300px;
            border-radius: 4px;
            border: none;
            margin-bottom: 12px;
        }
        button {
            background: #ff4444;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background: #ee2222;
        }
        .error {
            color: #f8d4d4;
            background: #a83232;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<?php include '../header.php'; ?>
<main>
    <h2>Chỉnh sửa thời gian dạy PT</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label for="trainer_id">Huấn luyện viên:</label>
        <select name="trainer_id" id="trainer_id" required>
            <option value="">-- Chọn PT --</option>
            <?php while ($t = $trainers->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>" <?= $t['id'] == $time_data['trainer_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="date">Ngày:</label>
        <input type="date" name="date" id="date" value="<?= $time_data['date'] ?>" required>

        <label for="start_time">Bắt đầu:</label>
        <input type="time" name="start_time" id="start_time" value="<?= $time_data['start_time'] ?>" required>

        <label for="end_time">Kết thúc:</label>
        <input type="time" name="end_time" id="end_time" value="<?= $time_data['end_time'] ?>" required>

        <button type="submit" name="update_pt_time">Cập nhật</button>
    </form>
</main>
</body>
</html>
