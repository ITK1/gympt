
<!-- add_member.php -->
 <?php require_once '../includes/config.php'; ?>
<main>
<form action="add_member.php" method="POST">
  <input name="name" required placeholder="Tên thành viên">
  <input name="email" type="email" required placeholder="Email">
  <input name="package" required placeholder="Gói tập">
  <button type="submit">Thêm</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("INSERT INTO members (name, email, package) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['package']);
  $stmt->execute();
  header('Location: members.php');
}
?>
</main>