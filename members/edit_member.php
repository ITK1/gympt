<!-- edit_member.php -->
<?php
require_once '../includes/config.php';
// include 'header.php';
$id = $_GET['id'] ?? 0;
$result = $conn->query("SELECT * FROM members WHERE id = $id");
$member = $result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("UPDATE members SET name=?, email=?, package=? WHERE id=?");
  $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['package'], $id);
  $stmt->execute();
  header("Location: members.php");
  exit;
}
?>
<main>
<h2>Chỉnh sửa thành viên</h2>
<form method="POST">
  <input name="name" value="<?= $member['name'] ?>" required>
  <input name="email" value="<?= $member['email'] ?>" required>
  <input name="package" value="<?= $member['package'] ?>" required>
  <button type="submit">Cập nhật</button>
</form>
</main>