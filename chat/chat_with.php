<?php
session_start();
require_once '../includes/config.php';

// Kiểm tra nếu chưa đăng nhập hoặc không phải member thì chuyển về trang đăng nhập
// Cho phép các role member, pt, admin đều được truy cập
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['member', 'pt', 'admin'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Lấy danh sách người dùng có thể chat (admin và pt)
$sql = "SELECT id, name, role FROM users WHERE role IN ('admin', 'pt')";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chọn người để chat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="./css/chat.css" />
  <link rel="stylesheet" href="./assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>

<div class="container my-5">
  <h2 class="mb-4">💬 Trò chuyện với Huấn luyện viên hoặc Admin</h2>

  <?php if ($result->num_rows > 0): ?>
    <ul class="list-group">
      <?php while ($u = $result->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <strong><?= htmlspecialchars($u['name']) ?></strong>
            <span class="badge bg-secondary ms-2"><?= htmlspecialchars(strtoupper($u['role'])) ?></span>
          </div>
          <a href="chat.php?chat_with=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary">💬 Chat</a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php else: ?>
    <div class="alert alert-warning">Không có người dùng nào để chat.</div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
