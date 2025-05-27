<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT schedules.id, trainers.name AS trainer_name, date, time, status FROM schedules JOIN trainers ON schedules.trainer_id = trainers.id WHERE member_id = ? ORDER BY date DESC, time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Lịch tập của tôi</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php require_once '../header.php'; ?>

<h2>Lịch tập của tôi</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Huấn luyện viên</th>
        <th>Ngày</th>
        <th>Giờ</th>
        <th>Trạng thái</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['trainer_name']) ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td>
            <?php 
                if ($row['status'] === 'pending') echo "Chờ duyệt";
                elseif ($row['status'] === 'approved') echo "<span style='color:green;'>Đã duyệt</span>";
                else echo "<span style='color:red;'>Từ chối</span>";
            ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
