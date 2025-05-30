<?php
require_once '../includes/config.php';
session_start();

// Kiểm tra quyền admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Xử lý duyệt yêu cầu
if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $request_id = $_GET['approve'];
    $stmt = $conn->prepare("UPDATE package_requests SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Gửi thông báo
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, message) 
        SELECT user_id, 'Yêu cầu đăng ký gói của bạn đã được duyệt. Vui lòng thanh toán để kích hoạt.' 
        FROM package_requests 
        WHERE id = ?
    ");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    header("Location: admin_package_requests.php");
    exit;
}

// Lấy danh sách yêu cầu
$result = $conn->query("
    SELECT pr.id, u.name AS user_name, pt.name AS pt_name, pr.package_name, pr.status, pr.created_at 
    FROM package_requests pr
    JOIN users u ON pr.user_id = u.id
    JOIN trainers pt ON pr.pt_id = pt.id
    ORDER BY pr.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Duyệt yêu cầu gói tập</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1c1c1c;
            color: white;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #ff2e2e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2e2e2e;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #ff2e2e;
        }
        tr:nth-child(even) {
            background-color: #3a3a3a;
        }
        a.btn {
            padding: 5px 10px;
            background-color: #00cc66;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a.btn:hover {
            background-color: #00994d;
        }
    </style>
</head>
<body>
    <h2>📦 Yêu cầu đăng ký gói tập</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Huấn luyện viên</th>
            <th>Gói</th>
            <th>Ngày yêu cầu</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['pt_name']) ?></td>
                    <td><?= htmlspecialchars($row['package_name']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                    <td>
                        <?= $row['status'] === 'approved' 
                            ? "<span style='color: lightgreen;'>ĐÃ DUYỆT</span>" 
                            : "<span style='color: orange;'>CHỜ DUYỆT</span>" ?>
                    </td>
                    <td>
                        <?php if ($row['status'] !== 'approved'): ?>
                            <a class="btn" href="?approve=<?= $row['id'] ?>" onclick="return confirm('Duyệt yêu cầu này?')">Duyệt</a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Không có yêu cầu nào.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>
