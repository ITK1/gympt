<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Lấy danh sách gói tập đã đăng ký để chọn
$registered = $conn->query("
    SELECT r.package_id, p.name 
    FROM registrations r
    JOIN packages p ON r.package_id = p.id
    WHERE r.user_id = $user_id
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_id = intval($_POST['package_id']);
    $amount = floatval($_POST['amount']);

    $stmt = $conn->prepare("INSERT INTO payments (user_id, package_id, amount, payment_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iid", $user_id, $package_id, $amount);
    $stmt->execute();

    echo "<p>Thanh toán thành công!</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Thanh toán</title>
  <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<?php include '../header.php'; ?>
<main>
  <h2>Thanh toán gói tập</h2>
  <form method="POST">
    <label for="package_id">Chọn gói:</label>
    <select name="package_id" required>
      <?php while ($row = $registered->fetch_assoc()): ?>
        <option value="<?= $row['package_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
      <?php endwhile; ?>
    </select><br><br>

    <label for="amount">Số tiền (VNĐ):</label>
    <input type="number" name="amount" step="0.01" required><br><br>

    <button type="submit">Thanh toán</button>
  </form>
</main>
</body>
</html>
