<?php
require_once '../includes/config.php';
include 'header.php';
$result = $conn->query("SELECT * FROM members");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Danh sách thành viên</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: white;
    }
    main {
      max-width: 1000px;
      margin: 60px auto;
      background: rgba(0,0,0,0.85);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px #ff2e2e;
    }
    h2 {
      text-align: center;
      color: #ff2e2e;
      margin-bottom: 30px;
    }
    a.button {
      display: inline-block;
      padding: 10px 20px;
      margin-bottom: 20px;
      background-color: #ff2e2e;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      transition: background-color 0.3s;
    }
    a.button:hover {
      background-color: #e60000;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1c1c1c;
    }
    th, td {
      border: 1px solid #333;
      padding: 12px;
      text-align: center;
    }
    th {
      background-color: #2e2e2e;
      color: #ff2e2e;
    }
    td a {
      color: #00d4ff;
      text-decoration: none;
      margin: 0 5px;
    }
    td a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <main>
    <h2>📋 Danh sách thành viên</h2>
    <a href="add_member.php" class="button">+ Thêm thành viên</a>
    <table>
      <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Gói tập</th><th>Hành động</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['package']) ?></td>
          <td>
            <a href="edit_member.php?id=<?= $row['id'] ?>">✏️ Sửa</a> |
            <a href="delete_member.php?id=<?= $row['id'] ?>" onclick="return confirm('Xoá thành viên này?')">🗑️ Xoá</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </main>
</body>
</html>
