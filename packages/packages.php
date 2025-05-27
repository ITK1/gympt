<?php
session_start();
require_once '../includes/config.php';

// Giả sử session lưu user info:
// $_SESSION['user'] = ['id' => 1, 'role' => 'admin', 'name' => 'Admin']
// hoặc ['id'=> 2, 'role'=>'customer', 'name'=>'Nguyễn Văn A']

$user = $_SESSION['user'] ?? null;
$isAdmin = $user && $user['role'] === 'admin';

// --- Xử lý thêm gói tập (chỉ admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package']) && $isAdmin) {
    $name = $_POST['name'];
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("INSERT INTO packages (name, duration, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $name, $duration, $price);
    $stmt->execute();

    header("Location: packages.php");
    exit;
}

// --- Xử lý xóa gói tập (chỉ admin)
if (isset($_GET['delete']) && $isAdmin) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM packages WHERE id = $id");
    header("Location: packages.php");
    exit;
}

// --- Xử lý đăng ký gói tập (chỉ khách)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_package']) && !$isAdmin && $user) {
    $package_id = intval($_POST['package_id']);
    $user_id = $user['id'];

    // Lưu đăng ký vào bảng registrations
    $stmt = $conn->prepare("INSERT INTO registrations (user_id, package_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $package_id);
    $stmt->execute();

    echo "<p>Đăng ký thành công!</p>";
}

// Lấy danh sách gói tập để hiển thị
$result = $conn->query("SELECT * FROM packages");

// Nếu là admin, load danh sách đăng ký để quản lý
if ($isAdmin) {
    $reg_sql = "SELECT r.id, u.name as user_name, u.phone, u.address, p.name as package_name, r.created_at
                FROM registrations r
                JOIN users u ON r.user_id = u.id
                JOIN packages p ON r.package_id = p.id
                ORDER BY r.created_at DESC";
    $reg_result = $conn->query($reg_sql);
}
?>

<main>
  <h2>Quản lý Gói tập</h2>

  <?php if ($isAdmin): ?>
    <!-- Form thêm gói tập -->
    <form method="POST" style="margin-bottom:20px;">
      <input type="text" name="name" placeholder="Tên gói tập" required>
      <input type="number" name="duration" placeholder="Số ngày" required min="1">
      <input type="number" name="price" step="0.01" placeholder="Giá (VNĐ)" required min="0">
      <button type="submit" name="add_package">Thêm gói</button>
    </form>
  <?php else: ?>
    <!-- Khách chỉ xem danh sách gói và đăng ký -->
    <h3>Đăng ký gói tập</h3>
  <?php endif; ?>

  <table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
      <th>ID</th><th>Tên</th><th>Thời gian (ngày)</th><th>Giá (VNĐ)</th>
      <?php if ($isAdmin): ?>
        <th>Hành động</th>
      <?php else: ?>
        <th>Đăng ký</th>
      <?php endif; ?>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['duration'] ?></td>
        <td><?= number_format($row['price'], 0, ',', '.') ?></td>

        <?php if ($isAdmin): ?>
          <td>
            <a href="packages.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa gói tập này?')">Xóa</a>
          </td>
        <?php else: ?>
          <td>
            <form method="POST" style="margin:0;">
              <input type="hidden" name="package_id" value="<?= $row['id'] ?>">
              <button type="submit" name="register_package">Đăng ký</button>
            </form>
          </td>
        <?php endif; ?>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php if ($isAdmin): ?>
    <h2>Danh sách người đăng ký</h2>
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
      <tr>
        <th>ID</th><th>Họ tên</th><th>SĐT</th><th>Địa chỉ</th><th>Gói tập</th><th>Ngày đăng ký</th>
      </tr>
      <?php while ($reg = $reg_result->fetch_assoc()): ?>
        <tr>
          <td><?= $reg['id'] ?></td>
          <td><?= htmlspecialchars($reg['user_name']) ?></td>
          <td><?= htmlspecialchars($reg['phone']) ?></td>
          <td><?= htmlspecialchars($reg['address']) ?></td>
          <td><?= htmlspecialchars($reg['package_name']) ?></td>
          <td><?= $reg['created_at'] ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php endif; ?>
</main>
