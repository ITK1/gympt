<?php
require_once '../includes/config.php';

// Giao diện chính
$result = $conn->query("SELECT * FROM trainers");
?>
<main>
<h2>Danh sách Huấn luyện viên</h2>
<table border="1">
<tr><th>ID</th><th>Tên</th><th>Chuyên môn</th><th>Giới thiệu</th><th>Email</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['name'] ?></td>
  <td><?= $row['specialty'] ?></td>
  <td><?= $row['bio'] ?></td>
  <td><?= $row['email'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</main>
