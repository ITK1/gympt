<?php
require_once '../includes/config.php';
$id = intval($_GET['id'] ?? 0);

$result = $conn->query("SELECT * FROM members WHERE id = $id");
$member = $result->fetch_assoc();
if (!$member) {
  die("Không tìm thấy thành viên.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("UPDATE members SET name=?, email=?, package=? WHERE id=?");
  $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['package'], $id);
  $stmt->execute();
  header("Location: members.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chỉnh sửa thành viên</title>
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container mt-5">
  <h2 class="mb-4">Chỉnh sửa thành viên</h2>
  <form method="POST" class="card p-4 shadow-sm">
    <div class="mb-3">
      <label class="form-label">Họ tên</label>
      <input name="name" type="text" class="form-control" value="<?= htmlspecialchars($member['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Gói tập</label>
      <input name="package" type="text" class="form-control" value="<?= htmlspecialchars($member['package']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="members.php" class="btn btn-secondary">Quay lại</a>
  </form>
</div>

<?php include '../includes/footer.php'; ?>


</body>
</html>
