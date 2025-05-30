<?php
session_start();
require_once '../includes/config.php';

$user = $_SESSION['user'] ?? null;
$isAdmin = $user && $user['role'] === 'admin';

// --- Admin thêm gói tập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package']) && $isAdmin) {
    $name = $_POST['name'];
    $duration = intval($_POST['duration']);
    $price = floatval($_POST['price']);

    $stmt = $conn->prepare("INSERT INTO packages (name, duration, price) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $name, $duration, $price);
    $stmt->execute();
    header("Location: /packages.php");
    exit;
}

// --- Admin xóa gói tập
if (isset($_GET['delete']) && $isAdmin) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM packages WHERE id = $id");
    header("Location: packages.php");
    exit;
}

// --- Khách đăng ký gói tập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_package']) && !$isAdmin && $user) {
    $package_id = intval($_POST['package_id']);
    $user_id = $user['id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $trainer_id = intval($_POST['trainer_id']);
    $schedule_date = $_POST['date'];
    $schedule_time = $_POST['time'];
    $method = $_POST['method'];

    $stmt = $conn->prepare("INSERT INTO training_requests (user_id, package_id, trainer_id, full_name, phone, email, method, schedule_date, schedule_time, status, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("iiissssss", $user_id, $package_id, $trainer_id, $full_name, $phone, $email, $method, $schedule_date, $schedule_time);
    $stmt->execute();

    echo "<p>Đăng ký thành công! Vui lòng chờ xác nhận.</p>";
}

// Lấy danh sách gói tập
$result = $conn->query("SELECT * FROM packages");

// Lấy danh sách PT
$trainers = $conn->query("SELECT * FROM trainers");

// Nếu là admin, lấy danh sách yêu cầu huấn luyện
if ($isAdmin) {
    $reg_sql = "SELECT tr.id, u.name as user_name, tr.full_name, tr.phone, tr.email, p.name as package_name, t.name as trainer_name, tr.schedule_date, tr.schedule_time, tr.method, tr.status
                FROM training_requests tr
                JOIN users u ON tr.user_id = u.id
                JOIN packages p ON tr.package_id = p.id
                JOIN trainers t ON tr.trainer_id = t.id
                ORDER BY tr.created_at DESC";
    $reg_result = $conn->query($reg_sql);
}
?>

<main>
<h2>Quản lý Gói tập</h2>

<?php if ($isAdmin): ?>
  <form method="POST" style="margin-bottom:20px;">
    <input type="text" name="name" placeholder="Tên gói tập" required>
    <input type="number" name="duration" placeholder="Số ngày" required min="1">
    <input type="number" name="price" step="0.01" placeholder="Giá (VNĐ)" required min="0">
    <button type="submit" name="add_package">Thêm gói</button>
  </form>
<?php else: ?>
  <h3>Đăng ký gói tập</h3>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
<tr>
  <th>ID</th><th>Tên</th><th>Thời gian (ngày)</th><th>Giá (VNĐ)</th>
  <?php if ($isAdmin): ?><th>Hành động</th><?php else: ?><th>Đăng ký</th><?php endif; ?>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= $row['duration'] ?></td>
  <td><?= number_format($row['price'], 0, ',', '.') ?></td>
  <?php if ($isAdmin): ?>
    <td><a href="packages.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa gói tập này?')">Xóa</a></td>
  <?php else: ?>
    <td>
      <form method="POST">
        <input type="hidden" name="package_id" value="<?= $row['id'] ?>">
        <input type="text" name="full_name" placeholder="Họ tên" required>
        <input type="text" name="phone" placeholder="Số điện thoại" required>
        <input type="email" name="email" placeholder="Email" required>
        <select name="trainer_id" required>
          <option value="">Chọn PT</option>
          <?php while ($t = $trainers->fetch_assoc()): ?>
            <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
          <?php endwhile; $trainers->data_seek(0); ?>
        </select>
        <input type="date" name="date" required>
        <input type="time" name="time" required>
        <select name="method" required>
          <option value="offline">Trực tiếp</option>
          <option value="online">Online</option>
        </select>
        <button type="submit" name="register_package">Đăng ký</button>
      </form>
    </td>
  <?php endif; ?>
</tr>
<?php endwhile; ?>
</table>

<?php if ($isAdmin): ?>
<h2>Danh sách yêu cầu huấn luyện</h2>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
<tr>
  <th>ID</th><th>Họ tên</th><th>SĐT</th><th>Email</th><th>Gói tập</th><th>PT</th><th>Ngày</th><th>Giờ</th><th>Hình thức</th><th>Trạng thái</th>
</tr>
<?php while ($reg = $reg_result->fetch_assoc()): ?>
<tr>
  <td><?= $reg['id'] ?></td>
  <td><?= htmlspecialchars($reg['full_name']) ?></td>
  <td><?= htmlspecialchars($reg['phone']) ?></td>
  <td><?= htmlspecialchars($reg['email']) ?></td>
  <td><?= htmlspecialchars($reg['package_name']) ?></td>
  <td><?= htmlspecialchars($reg['trainer_name']) ?></td>
  <td><?= $reg['schedule_date'] ?></td>
  <td><?= $reg['schedule_time'] ?></td>
  <td><?= $reg['method'] ?></td>
  <td><?= $reg['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php endif; ?>
</main>
