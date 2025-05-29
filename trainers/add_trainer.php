<?php require_once '../includes/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <title>Thêm Huấn luyện viên - PT Gym</title>
  <style>
    body {
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #eee;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0; padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 40px 20px;
    }
    main {
      background-color: rgba(0, 0, 0, 0.85);
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px #ff2e2e;
      width: 100%;
      max-width: 480px;
    }
    h2 {
      color: #ff2e2e;
      text-align: center;
      margin-bottom: 25px;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    input, textarea, button {
      font-size: 16px;
      padding: 10px 12px;
      border-radius: 6px;
      border: none;
      outline: none;
      transition: 0.3s ease;
    }
    input, textarea {
      background-color: #222;
      color: #eee;
      border: 1px solid #444;
      resize: vertical;
    }
    input::placeholder, textarea::placeholder {
      color: #999;
    }
    input:focus, textarea:focus {
      border-color: #ff2e2e;
      box-shadow: 0 0 8px #ff2e2e;
    }
    button {
      background-color: #ff2e2e;
      color: white;
      cursor: pointer;
      font-weight: 600;
      border: none;
    }
    button:hover {
      background-color: #e02525;
    }
  </style>
</head>
<body>
  <main>
    <h2>Thêm Huấn luyện viên</h2>
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
      exit();
    }
    ?>
  </main>
</body>
</html>
