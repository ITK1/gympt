<!-- add_trainer.php -->
<?php require_once '../includes/config.php'; ?>
<main>
<h2>Thêm huấn luyện viên</h2>
<form method="POST">
  <input name="name" required placeholder="Tên">
  <input name="email" type="email" required placeholder="Email">
  <input name="specialty" required placeholder="Chuyên môn">
  <textarea name="bio" placeholder="Giới thiệu"></textarea>
  <button type="submit">Thêm</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("INSERT INTO trainers (name, email, specialty, bio) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['specialty'], $_POST['bio']);
  $stmt->execute();
  header("Location: trainers.php");
}
?>
</main>