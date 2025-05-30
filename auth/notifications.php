<?php
require './includes/config.php';
session_start();

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$result = $conn->query("SELECT * FROM notifications WHERE user_id = $user_id AND role = '$role' ORDER BY created_at DESC");

echo "<h2>Thông báo của bạn</h2>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>[" . $row['created_at'] . "] " . htmlspecialchars($row['message']) . "</li>";
}
echo "</ul>";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông báo</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- nếu có -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #fff;
            padding: 20px;
        }
        .container {
            background: #222;
            padding: 20px;
            border-radius: 10px;
        }
        h2 {
            color: #ff2e2e;
        }
        .notification {
            padding: 10px;
            border-bottom: 1px solid #444;
        }
        .notification.unread {
            background-color: #333;
            font-weight: bold;
        }
        .notification.read {
            opacity: 0.6;
        }
        .notification small {
            color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔔 Thông báo cho <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</h2>
        <?php
        $stmt = $conn->prepare("SELECT id, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $class = $row['is_read'] ? 'read' : 'unread';
                ?>
                <div class="notification <?= $class ?>">
                    <?= htmlspecialchars($row['message']) ?>
                    <br><small>🕒 <?= $row['created_at'] ?></small>
                </div>
            <?php endwhile;
        else:
            echo "<p>Không có thông báo nào.</p>";
        endif;
        ?>
    </div>
</body>
</html>
