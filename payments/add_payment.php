<!-- add_payment.php -->
<?php
require_once '../includes/config.php'; 
$members = $conn->query("SELECT id, name FROM members");
?>
<main>
<h2>Thêm thanh toán</h2>
<form method="POST">
  <select name="member_id">
    <?php while ($m = $members->fetch_assoc()): ?>
      <option value="<?= $m['id'] ?>"><?= $m['name'] ?></option>
    <?php endwhile; ?>
  </select>
  <input name="amount" type="number" step="1000" placeholder="Số tiền">
  <input name="payment_date" type="date">
  <input name="method" placeholder="Phương thức (Chuyển khoản, Tiền mặt)">
  <button type="submit">Thêm</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("INSERT INTO payments (member_id, amount, payment_date, method) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("idss", $_POST['member_id'], $_POST['amount'], $_POST['payment_date'], $_POST['method']);
  $stmt->execute();
  header("Location: payments.php");
}
?>
</main>
