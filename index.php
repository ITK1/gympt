<?php
require_once './includes/config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Trang Chủ - PT Gym</title>
  <link rel="stylesheet" href="/assets/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0; padding: 0; background: #f7f7f7;
    }
    .hero {
      background: url('https://images.unsplash.com/photo-1598970434795-0c54fe7c0642?auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
      height: 400px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
    }
    .hero-content h1 {
      font-size: 3rem;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
    }
    .hero-content p {
      font-size: 1.3rem;
      margin-bottom: 1.5rem;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
    }
    .cta-button {
      background: #ff4c60;
      padding: 12px 25px;
      color: white;
      text-decoration: none;
      font-weight: 600;
      border-radius: 30px;
      box-shadow: 0 5px 15px rgba(255,76,96,0.4);
      transition: background 0.3s ease;
    }
    .cta-button:hover {
      background: #e03e53;
    }
    .features {
      display: flex;
      justify-content: center;
      margin: 40px 0;
      gap: 40px;
    }
    .feature {
      background: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
      width: 250px;
      text-align: center;
      font-size: 1.1rem;
    }
    .feature h3 {
      font-size: 1.5rem;
      margin-bottom: 10px;
    }
    .trainers-section {
      max-width: 1000px;
      margin: 50px auto;
      padding: 0 20px;
    }
    .trainers-section h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2rem;
    }
    .trainers-list {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 25px;
    }
    .trainer-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
      width: 220px;
      padding: 15px;
      text-align: center;
    }
    .trainer-card img {
      width: 100%;
      height: 180px;
      border-radius: 15px;
      object-fit: cover;
      margin-bottom: 15px;
    }
    .trainer-card h4 {
      margin: 0 0 10px 0;
    }
    .trainer-card p {
      font-size: 0.9rem;
      color: #555;
    }
    footer {
      text-align: center;
      padding: 15px 0;
      background: #222;
      color: white;
      margin-top: 40px;
    }
  </style>
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
      <p>Chọn ngày giờ phù hợp và nhận hướng dẫn từ PT.</p>
    </div>
    <div class="feature">
      <h3>💪 Gợi ý bài tập</h3>
      <p>Tham khảo các bài tập ngực, bụng, tay phù hợp thể trạng.</p>
    </div>
    <div class="feature">
      <h3>💬 Chat với PT</h3>
      <p>Trò chuyện trực tiếp với huấn luyện viên qua hệ thống.</p>
    </div>
  </section>

  <section class="trainers-section">
    <h2>Huấn luyện viên nổi bật</h2>
    <div class="trainers-list">
      <!-- Demo trainer cards tĩnh -->
      <div class="trainer-card">
        <img src="hinhhinh" alt="Trainer 1" />
        <h4>Nguyễn Văn A</h4>
        <p>Chuyên môn: Tăng cơ, giảm mỡ</p>
        <p>Hơn 5 năm kinh nghiệm huấn luyện cá nhân.</p>
      </div>
      <div class="trainer-card">
        <img src="hinhhinh" alt="Trainer 2" />
        <h4>Trần Thị B</h4>
        <p>Chuyên môn: Yoga, phục hồi chấn thương</p>
        <p>Hỗ trợ khách hàng tập luyện đúng kỹ thuật.</p>
      </div>
      <div class="trainer-card">
        <img src="hinh" alt="Trainer 3" />
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
