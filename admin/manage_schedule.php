<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'pt'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Nếu PT thì chỉ xem lịch của mình
if ($role === 'pt') {
    $stmt = $conn->prepare("SELECT schedules.id, members.name AS member_name, date, time, status FROM schedules JOIN members ON schedules.member_id = members.id WHERE trainer_id = ? ORDER BY date, time");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Admin xem tất cả
    $result = $conn->query("SELECT schedules.id, members.name AS member_name, trainers.name AS trainer_name, date, time, status FROM schedules JOIN members ON schedules.member_id = members.id JOIN trainers ON schedules.trainer_id = trainers.id ORDER BY date, time");
}

// Xử lý duyệt hoặc từ chối
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if (in_array($action, ['approve', 'reject'])) {
        $new_status = $action === 'approve' ? 'approved' : 'rejected';
        $stmt2 = $conn->prepare("UPDATE schedules SET status = ? WHERE id = ?");
        $stmt2->bind_param("si", $new_status, $id);
        $stmt2->execute();
        header("Location: manage_schedule.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Quản lý lịch tập</title>
</head>
<body>
<?php include '../header.php'; ?>

<h2>Quản lý lịch tập</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Thành viên</th>
        <?php if ($role === 'admin') echo "<th>Huấn luyện viên</th>"; ?>
        <th>Ngày</th>
        <th>Giờ</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['member_name']) ?></td>
        <?php if ($role === 'admin'): ?>
            <td><?= htmlspecialchars($row['trainer_name']) ?></td>
        <?php endif; ?>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td>
            <?php 
                if ($row['status'] === 'pending') echo "Chờ duyệt";
                elseif ($row['status'] === 'approved') echo "<span style='color:green;'>Đã duyệt</span>";
                else echo "<span style='color:red;'>Từ chối</span>";
            ?>
        </td>
        <td>
            <?php if ($row['status'] === 'pending'): ?>
                <a href="?action=approve&id=<?= $row['id'] ?>">Duyệt</a> | 
                <a href="?action=reject&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn từ chối?');">Từ chối</a>
            <?php else: ?>
                Không có
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
