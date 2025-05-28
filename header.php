<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$role = $_SESSION['role'] ?? null;
?>
<link rel="stylesheet" href="./assets/style.css">
<header class="main-header">
  <nav class="navbar">
    <div class="logo">
      <a href="index.php">🏋️‍♂️ PT GYM</a>
    </div>
    <ul class="nav-links">
      <li><a href="../index.php">Trang chủ</a></li>
      <li><a href="./bmi/bmi.php">Tính BMI</a></li>
      <?php if ($isLoggedIn): ?>
        <?php if ($role === 'admin'): ?>
          <li><a href="./admin/admin.php">Trang quản trị</a></li>
          <li><a href="./admin/manage_schedule.php">Quản lý lịch tập</a></li>
          <li><a href="./auth/logout.php">Đăng xuất</a></li>
        <?php elseif ($role === 'pt'): ?>
          <li><a href="./pt/profile.php">Trang PT</a></li>
          <li><a href="./admin/manage_schedule.php">Quản lý lịch tập</a></li>
          <li><a href="./auth/logout.php">Đăng xuất</a></li>
        <?php else: /* Khách hàng (customer) */ ?>
          <li><a href="./schedules/book_schedule.php">Đặt lịch tập</a></li>
          <li><a href="./pt/my_schedule.php">Lịch tập của tôi</a></li>
          <li><a href="./customer/add_payment.php">Thanh toán</a></li> <!-- ✅ Mục mới thêm -->
          <li><a href="./auth/logout.php">Đăng xuất</a></li>
        <?php endif; ?>
      <?php else: ?>
        <li><a href="./auth/login.php">Đăng nhập</a></li>
        <li><a href="./auth/register.php">Đăng ký</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</header>
