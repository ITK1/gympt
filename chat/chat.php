<?php
session_start();
require_once '../includes/config.php';

// Kiểm tra user đã đăng nhập và là member
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy biến chat_with từ GET, ép kiểu int
$chat_with = isset($_GET['chat_with']) ? intval($_GET['chat_with']) : 0;

// Nếu chưa chọn người chat thì redirect về danh sách chọn người chat
if ($chat_with <= 0) {
    header('Location: ./chat_with.php'); // đổi tên file nếu bạn đặt khác
    exit;
}

// Kiểm tra người chat_with có tồn tại trong DB và role phải là admin hoặc pt
$stmt = $conn->prepare("SELECT id, name FROM users WHERE id = ? AND role IN ('admin', 'pt')");
$stmt->bind_param("i", $chat_with);
$stmt->execute();
$result = $stmt->get_result();
$chat_user = $result->fetch_assoc();

if (!$chat_user) {
    die("Người dùng không tồn tại hoặc không thể chat với bạn.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Chat với <?= htmlspecialchars($chat_user['name']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./css/chat.css" />
  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>

<div class="container my-4">
  <h2>Chat với <?= htmlspecialchars($chat_user['name']) ?></h2>

  <div id="chat-box" class="border rounded p-3 mb-3" style="height:400px; overflow-y:auto; background:#f8f9fa;">
    <!-- Tin nhắn sẽ được load ở đây bằng ajax -->
  </div>

  <form id="chat-form" class="d-flex" autocomplete="off">
    <input type="hidden" id="chat_with" value="<?= $chat_with ?>" />
    <input type="text" id="message" class="form-control me-2" placeholder="Nhập tin nhắn..." required />
    <button type="submit" class="btn btn-primary">Gửi</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/chat.js"></script>
</body>
</html>
