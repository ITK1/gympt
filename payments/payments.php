<?php
require_once '../includes/config.php';
include 'header.php';
$result = $conn->query("SELECT payments.id, members.name AS member_name, amount, payment_date, method FROM payments JOIN members ON payments.member_id = members.id");
?>
<link rel="stylesheet" href="assets/style.css">
<main>
<h2>Thanh toán</h2>
<table border="1">
<tr><th>ID</th><th>Thành viên</th><th>Số tiền</th><th>Ngày thanh toán</th><th>Phương thức</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['member_name'] ?></td>
  <td><?= number_format($row['amount'], 0, ',', '.') ?> VND</td>
  <td><?= $row['payment_date'] ?></td>
  <td><?= $row['method'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</main>