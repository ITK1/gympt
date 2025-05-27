<!-- CRUD packages: edit_package.php -->
<?php
require_once './includes/config.php';
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("UPDATE packages SET name=?, duration=?, price=? WHERE id=?");
  $stmt->bind_param("sidi", $_POST['name'], $_POST['duration'], $_POST['price'], $id);
  $stmt->execute();
  header("Location: packages.php");
} else {
  $data = $conn->query("SELECT * FROM packages WHERE id=$id")->fetch_assoc();
}
?>
<form method="POST">
  <input name="name" value="<?= $data['name'] ?>">
  <input name="duration" type="number" value="<?= $data['duration'] ?>">
  <input name="price" type="number" value="<?= $data['price'] ?>">
  <button type="submit">Cập nhật</button>
</form>
