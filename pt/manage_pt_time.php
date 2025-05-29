<?php
require_once '../includes/config.php';
session_start();

// Kiểm tra đăng nhập và phân quyền admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$role = strtolower($_SESSION['role'] ?? '');

if ($role == 'pt,member') {
    header("Location: ../index.php");
    exit;
}

// Xử lý thêm thời gian dạy PT mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pt_time'])) {
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Validate đơn giản
    if ($trainer_id > 0 && $date && $start_time && $end_time) {
        $stmt = $conn->prepare("INSERT INTO pt_times (trainer_id, date, start_time, end_time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $trainer_id, $date, $start_time, $end_time);
        if ($stmt->execute()) {
            header("Location: manage_pt_time.php?msg=added");
            exit;
        } else {
            $error = "Lỗi thêm thời gian dạy: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin.";
    }
}

// Xử lý xóa thời gian dạy
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM pt_times WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_pt_time.php?msg=deleted");
        exit;
    }
}

// Lấy danh sách thời gian dạy PT
$result = $conn->query("
    SELECT pt_times.id, trainers.name AS trainer_name, pt_times.date, pt_times.start_time, pt_times.end_time 
    FROM pt_times
    JOIN trainers ON pt_times.trainer_id = trainers.id
    ORDER BY pt_times.date DESC, pt_times.start_time DESC
");

// Lấy danh sách PT để chọn
$trainers = $conn->query("SELECT id, name FROM trainers ORDER BY name");

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Quản lý thời gian dạy PT</title>
    <link rel="stylesheet" href="../assets/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #eee;
            margin: 0; padding: 0;
        }
        main {
            max-width: 900px;
            margin: 30px auto;
            background: #222;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ff4444;
        }
        h2, h3 {
            color: #ff5555;
        }
        form label {
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
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #ee2222;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #333;
            color: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #555;
            text-align: center;
        }
        th {
            background: #ff4444;
        }
        tr:nth-child(even) {
            background: #2b2b2b;
        }
        a {
            color: #ff7777;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            font-weight: bold;
        }
        .message.success {
            background: #2a7a2a;
            color: #d4f8d4;
        }
        .message.error {
            background: #a83232;
            color: #f8d4d4;
        }
    </style>
</head>
<body>
<?php include '../header.php'; ?>
<main>
    <h2>Quản lý Thời gian dạy PT</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="message success">
            <?php
            if ($_GET['msg'] === 'added') echo "Thêm thời gian dạy thành công.";
            elseif ($_GET['msg'] === 'deleted') echo "Xóa thời gian dạy thành công.";
            ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="message error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h3>Thêm thời gian dạy PT mới</h3>
    <form method="POST" action="">
        <label for="trainer_id">Huấn luyện viên:</label>
        <select name="trainer_id" id="trainer_id" required>
            <option value="">-- Chọn PT --</option>
            <?php while ($t = $trainers->fetch_assoc()): ?>
                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="date">Ngày:</label>
        <input type="date" name="date" id="date" required />

        <label for="start_time">Bắt đầu:</label>
        <input type="time" name="start_time" id="start_time" required />

        <label for="end_time">Kết thúc:</label>
        <input type="time" name="end_time" id="end_time" required />

        <button type="submit" name="add_pt_time">Thêm thời gian dạy</button>
    </form>

    <h3>Danh sách thời gian dạy PT</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Huấn luyện viên</th>
                <th>Ngày</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="6">Chưa có dữ liệu thời gian dạy PT.</td></tr>
        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['trainer_name']) ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['start_time'] ?></td>
                    <td><?= $row['end_time'] ?></td>
                    <td>
                        <a href="edit_pt_time.php?id=<?= $row['id'] ?>">Sửa</a> |
                        <a href="manage_pt_time.php?delete=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa thời gian này?');">Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
                