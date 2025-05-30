<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null;
$isAdmin = $role === 'admin';
?>
<link rel="stylesheet" href="/assets/style.css">
<link rel="stylesheet" href="/assets/styles-extra.css">

<header class="main-header">
  <nav class="navbar">
    <div class="logo">
      <a href="/index.php"><h1 class="dumbbell-title">🏋️‍♂️ PT GYM</h1></a>
    </div>
    <ul class="nav-links">
      <li><a href="/index.php">Trang chủ</a></li>
      <li><a href="/bmi/bmi.php">Tính BMI</a></li>
      <?php if ($isLoggedIn): ?>
        <?php if ($isAdmin): ?>
          <li><a href="/auth/profile.php">Trang Cá Nhân</a></li>
          <li><a href="/admin/admin.php">Trang quản trị</a></li>
          <li><a href="/payments/approve.php">Duyệt PT</a></li>
          <li><a href="/payments/approve_request.php">Duyệt gói tập</a></li>
          <li><a href="/admin/manage_schedule.php">QL lịch tập</a></li>
        <?php elseif ($role === 'pt'): ?>
          <li><a href="/auth/profile.php">Trang Cá Nhân</a></li>
          <li><a href="/trainer/trainers">Trang PT</a></li>
          <li><a href="/admin/manage_schedule.php">QL lịch dạy</a></li>
        <?php else: ?>
          <li><a href="/packages/request_package.php">Đăng ký gói tập</a></li>
          <li><a href="/auth/profile.php">Trang Cá Nhân</a></li>
          <li><a href="/schedules/book_schedule.php">Đặt lịch tập</a></li>
          <li><a href="/pt/my_schedule.php">Lịch tập của tôi</a></li>
          <li><a href="/customer/add_payment.php">Thanh toán</a></li>
        <?php endif; ?>
        <li><a href="/auth/logout.php">Đăng xuất</a></li>
      <?php else: ?>
        <li><a href="/auth/login.php">Đăng nhập</a></li>
        <li><a href="/auth/register.php">Đăng ký</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
