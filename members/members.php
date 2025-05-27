
<?php
require_once '../includes/config.php';
include 'header.php';
$result = $conn->query("SELECT * FROM members");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <main>
<h2>Danh sách thành viên</h2>
<a href="add_member.php">+ Thêm thành viên</a>
<table border="1">
<tr><th>ID</th><th>Tên</th><th>Email</th><th>Gói tập</th><th>Hành động</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['name'] ?></td>
  <td><?= $row['email'] ?></td>
  <td><?= $row['package'] ?></td>
  <td>
    <a href="edit_member.php?id=<?= $row['id'] ?>">Sửa</a> |
    <a href="delete_member.php?id=<?= $row['id'] ?>" onclick="return confirm('Xoá thành viên này?')">Xoá</a>
  </td>
</tr>
<?php endwhile; ?>
</table>
</main>
</body>
</html>
