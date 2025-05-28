<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'pt'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role === 'pt') {
    $stmt = $conn->prepare("SELECT s.id, m.name AS member_name, s.date, s.time, s.status, p.payment_status 
        FROM schedules s 
        JOIN members m ON s.member_id = m.id 
        LEFT JOIN payments p ON s.id = p.schedule_id 
        WHERE s.trainer_id = ? 
        ORDER BY s.date, s.time");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT s.id, m.name AS member_name, t.name AS trainer_name, s.date, s.time, s.status, p.payment_status 
        FROM schedules s 
        JOIN members m ON s.member_id = m.id 
        JOIN trainers t ON s.trainer_id = t.id 
        LEFT JOIN payments p ON s.id = p.schedule_id 
        ORDER BY s.date, s.time");
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if (in_array($action, ['approve', 'reject'])) {
        // Kiểm tra payment đã thanh toán chưa
        $stmtCheck = $conn->prepare("SELECT payment_status FROM payments WHERE schedule_id = ?");
        $stmtCheck->bind_param("i", $id);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        $payment = $resCheck->fetch_assoc();

        if ($payment && $payment['payment_status'] === 'paid') {
            $new_status = $action === 'approve' ? 'approved' : 'rejected';
            $stmt2 = $conn->prepare("UPDATE schedules SET status = ? WHERE id = ?");
            $stmt2->bind_param("si", $new_status, $id);
            $stmt2->execute();
        } else {
            // Chưa thanh toán, không cho duyệt
            $_SESSION['msg'] = "Không thể duyệt lịch chưa thanh toán!";
        }
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

<?php if (!empty($_SESSION['msg'])) {
    echo "<p style='color:red;'>" . $_SESSION['msg'] . "</p>";
    unset($_SESSION['msg']);
} ?>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Thành viên</th>
        <?php if ($role === 'admin') echo "<th>Huấn luyện viên</th>"; ?>
        <th>Ngày</th>
        <th>Giờ</th>
        <th>Trạng thái</th>
        <th>Thanh toán</th>
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
            <?php
            if ($row['payment_status'] === 'paid') echo "<span style='color:green;'>Đã thanh toán</span>";
            elseif ($row['payment_status'] === 'pending') echo "<span style='color:orange;'>Chưa thanh toán</span>";
            else echo "Không có";
            ?>
        </td>
        <td>
            <?php if ($row['status'] === 'pending' && $row['payment_status'] === 'paid'): ?>
                <a href="?action=approve&id=<?= $row['id'] ?>">Duyệt</a> | 
                <a href="?action=reject&id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn từ chối?');">Từ chối</a>
            <?php elseif ($row['status'] === 'pending' && $row['payment_status'] !== 'paid'): ?>
                <span style="color:red;">Chờ thanh toán</span>
            <?php else: ?>
                Không có
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
