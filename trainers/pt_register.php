<?php
require_once './includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $target = "uploads/" . basename($_FILES['photo']['name']);
  move_uploaded_file($_FILES['photo']['tmp_name'], $target);

  $stmt = $conn->prepare("INSERT INTO trainers (name, email, phone, specialization, experience_years, photo, approved) VALUES (?, ?, ?, ?, ?, ?, 0)");
  $stmt->bind_param("ssssis", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['specialization'], $_POST['experience'], $target);
  $stmt->execute();
  echo "Đã gửi yêu cầu, chờ admin duyệt.";
}
?>
<form method="POST" enctype="multipart/form-data">
  <input name="name" placeholder="Tên PT">
  <input name="email" type="email" placeholder="Email">
  <input name="phone" placeholder="SĐT">
  <input name="specialization" placeholder="Chuyên môn">
  <input name="experience" type="number" placeholder="Kinh nghiệm (năm)">
  <input name="photo" type="file">
  <button type="submit">Gửi đăng ký PT</button>
</form>