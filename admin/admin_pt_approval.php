<?php
require '../includes/config.php';

$pending = $conn->query("SELECT * FROM trainers WHERE approval_status = 'pending'");
$approved = $conn->query("SELECT * FROM trainers WHERE approval_status = 'approved'");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Duyệt Huấn Luyện Viên</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/style.css">
  <style>
    .trainer-card3 {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 15px;
      background: #fff;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
    }
    .trainer-card3 img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 20px;
      border: 2px solid #ccc;
    }
    .trainer-info {
      flex-grow: 1;
    }
    body {
      background: #f8f9fa;
    }
    .container {
      max-width: 900px;
      margin: 40px auto;
    }
  </style>
</head>
<body>

<?php require_once '../header.php'?>
<div class="container">
  <h2 class="mb-4 text-primary">💼 Huấn Luyện Viên Chờ Duyệt</h2>

  <?php if ($pending->num_rows === 0): ?>
    <div class="alert alert-info">Không có huấn luyện viên nào đang chờ duyệt.</div>
  <?php else: ?>
    <?php while ($pt = $pending->fetch_assoc()): ?>
      <div class="trainer-card3">
        <img src="<?= htmlspecialchars($pt['photo_url']) ?>" alt="PT">
        <div class="trainer-info">
          <h5><?= htmlspecialchars($pt['name']) ?></h5>
          <p><strong>Chuyên môn:</strong> <?= htmlspecialchars($pt['specialty']) ?></p>
          <p><strong>Loại dạy:</strong> <?= htmlspecialchars($pt['teach_type']) ?> | <strong>Địa điểm:</strong> <?= htmlspecialchars($pt['location']) ?></p>
        </div>
        <a href="approve_pt.php?id=<?= $pt['id'] ?>" class="btn btn-success">✔️ Duyệt</a>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <hr class="my-5">

  <h2 class="mb-4 text-success">✅ Huấn Luyện Viên Đã Duyệt</h2>

  <?php if ($approved->num_rows === 0): ?>
    <div class="alert alert-secondary">Chưa có huấn luyện viên nào được duyệt.</div>
  <?php else: ?>
    <?php while ($pt = $approved->fetch_assoc()): ?>
      <div class="trainer-card3">
        <img src="<?= htmlspecialchars($pt['photo_url']) ?>" alt="PT">
        <div class="trainer-info">
          <h5><?= htmlspecialchars($pt['name']) ?></h5>
          <p><strong>Chuyên môn:</strong> <?= htmlspecialchars($pt['specialty']) ?></p>
          <p><strong>Loại dạy:</strong> <?= htmlspecialchars($pt['teach_type']) ?> | <strong>Địa điểm:</strong> <?= htmlspecialchars($pt['location']) ?></p>
        </div>
        <span class="badge bg-success p-2">Đã duyệt</span>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
</div>
</body>
</html>
