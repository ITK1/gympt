<?php 
require_once '../includes/config.php'; 
include '../header.php'; // Đảm bảo bạn đã tạo file header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Document</title>
</head>
<body>
  <main class="container mt-5">
  <h2 class="mb-4">Thêm Thành Viên Mới</h2>
  
  <form action="add_member.php" method="POST" class="card p-4 shadow-sm" style="max-width: 500px;">
    <div class="mb-3">
      <label for="name" class="form-label">Tên thành viên</label>
      <input name="name" id="name" class="form-control" required placeholder="Nguyễn Văn A">
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input name="email" id="email" type="email" class="form-control" required placeholder="email@example.com">
    </div>

    <div class="mb-3">
      <label for="package" class="form-label">Gói tập</label>
      <input name="package" id="package" class="form-control" required placeholder="Gói 3 tháng">
    </div>

    <button type="submit" class="btn btn-primary">Thêm</button>
  </form>
</main>
</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("INSERT INTO members (name, email, package) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $_POST['name'], $_POST['email'], $_POST['package']);
  $stmt->execute();
  header('Location: members.php');
  exit;
}
?>

<?php include '../includes/footer.php'; // Đảm bảo file này tồn tại ?>
