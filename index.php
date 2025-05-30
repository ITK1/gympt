
<?php
include 'visitor_log.php';  // Ghi log ngay khi khách vào trang
session_start();
// Ghi log lượt truy cập
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}
$logFile = $logDir . '/visitors.log';

$ip = $_SERVER['REMOTE_ADDR'];
$time = date('Y-m-d H:i:s');
file_put_contents($logFile, "$time - $ip\n", FILE_APPEND);
?>

<?php
require_once './includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Trang Chủ - PT Gym</title>
  <!-- <link rel="stylesheet" href="/assets/style.css" /> -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/assets/style.css">
    <script src="/assets/gym-effects.js"></script>

  
</head>
<body>
  
  <?php require_once './header.php'; ?>
  <section class="hero">
    <div class="hero-content">
      <h1>Chào mừng đến với <span class="highlight">PT GYM</span></h1>
      <p>Giúp bạn đạt mục tiêu: giảm cân, tăng cơ, cải thiện sức khỏe</p>
      <a href="./packages/packages.php" class="cta-button">Đăng ký gói tập ngay</a>
    </div>
  </section>

  <section class="features">
    <div class="feature">
      <h3>📅 Lịch tập cá nhân</h3>
      <a href="./schedules/book_schedule.php">
      <p>Chọn ngày giờ phù hợp và nhận hướng dẫn từ PT.</p>
      </a>
    </div>
    <div class="feature">
      <a href="./exercises.php">
      <h3>💪 Gợi ý bài tập</h3>
      <p>Tham khảo các bài tập ngực, bụng, tay phù hợp thể trạng.</p>
      </a>
    </div>
    <div class="feature">
      <a href="./chat/chat.php">
      <h3>💬 Chat với PT</h3>
      <p>Trò chuyện trực tiếp với huấn luyện viên qua hệ thống.</p>
      </a>
    </div>
  </section>

  <section class="trainers-section">
    <h2>Huấn luyện viên nổi bật</h2>
    <div class="trainers-list">
      <!-- Demo trainer cards tĩnh -->
      <div class="trainer-card">
        <img src="./img/anhmau.jpg" alt="Trainer 1" />
        <h4>Nguyễn Văn A</h4>
        <p>Chuyên môn: Tăng cơ, giảm mỡ</p>
        <p>Hơn 5 năm kinh nghiệm huấn luyện cá nhân.</p>
      </div>
      <div class="trainer-card">
        <img src="./img/anhmau2.jpg" alt="Trainer 2" />
        <h4>Trần Thị B</h4>
        <p>Chuyên môn: Yoga, phục hồi chấn thương</p>
        <p>Hỗ trợ khách hàng tập luyện đúng kỹ thuật.</p>
      </div>
      <div class="trainer-card">
        <img src="./img/anhmau3.jpg" alt="Trainer 3" />
        <h4>Phạm Văn C</h4>
        <p>Chuyên môn: Cardio, thể hình</p>
        <p>Giúp tăng sức bền và cải thiện vóc dáng.</p>
      </div>
    </div>
  </section>
<!-- <section class="trainers-section">
  <h2>Huấn luyện viên nổi bật</h2>
  <div class="trainers-list">
    <?php
      $trainers = $conn->query("SELECT name, specialty, bio, photo_url FROM trainers LIMIT 6");
      while ($trainer = $trainers->fetch_assoc()):
    ?>
    <div class="trainer-card">
      <img src="<?= htmlspecialchars($trainer['photo_url'] ?: 'https://via.placeholder.com/400x300?text=No+Image') ?>" alt="<?= htmlspecialchars($trainer['name']) ?>" />
      <h4><?= htmlspecialchars($trainer['name']) ?></h4>
      <p><strong>Chuyên môn:</strong> <?= htmlspecialchars($trainer['specialty']) ?></p>
      <p><?= htmlspecialchars($trainer['bio']) ?></p>
    </div>
    <?php endwhile; ?>
  </div>
</section> -->

  <footer>
    <p>© 2025 PT GYM. All rights reserved.</p>
  </footer>
</body>
</html>
