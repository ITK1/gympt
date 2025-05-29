<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];

$result = $conn->query("SELECT * FROM trainers");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Danh sách Huấn luyện viên - PT Gym</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
      margin: 0; padding: 0;
    }
    .container {
      max-width: 1000px;
      margin: 50px auto;
      background-color: rgba(0,0,0,0.85);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px #ff2e2e;
    }
    h2 {
      color: #ff2e2e;
      text-align: center;
      margin-bottom: 25px;
      font-weight: 700;
      letter-spacing: 1px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #1a1a1a;
      color: white;
      border-radius: 6px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(255, 46, 46, 0.3);
    }
    th, td {
      padding: 12px;
      border: 1px solid #444;
      text-align: center;
    }
    th {
      background-color: #ff2e2e;
      color: white;
      font-weight: 600;
    }
    tr:nth-child(even) {
      background-color: #2e2e2e;
    }
    tr:hover {
      background-color: #ff2e2e33;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Danh sách Huấn luyện viên</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Chuyên môn</th>
          <th>Giới thiệu</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['specialty']) ?></td>
          <td style="text-align:left;"><?= nl2br(htmlspecialchars($row['bio'])) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
