<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'pt'])) {
    header('Location: ../auth/login.php');
    exit;
}

$my_id = $_SESSION['user_id'];

// Lấy danh sách user đã từng chat với PT/Admin
$stmt = $conn->prepare("SELECT DISTINCT from_user, users.name 
                        FROM messages 
                        JOIN users ON messages.from_user = users.id 
                        WHERE to_user = ? 
                        ORDER BY messages.timestamp DESC");
$stmt->bind_param("i", $my_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách tin nhắn đến</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-4">
    <h2>Khách hàng đã nhắn</h2>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <a href="chat.php?chat_with=<?= $row['from_user'] ?>">
                    <?= htmlspecialchars($row['name']) ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
