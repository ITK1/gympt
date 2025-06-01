<?php require_once '../header.php';?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tính BMI & TDEE</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
    }
    .container {
      max-width: 400px;
      margin: 80px auto;
      background-color: rgba(0,0,0,0.85);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px #ff2e2e;
    }
    h2 {
      text-align: center;
      color: #ff2e2e;
      margin-bottom: 25px;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    input, select {
      padding: 12px;
      margin-bottom: 15px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
    }
    button {
      padding: 12px;
      background-color: #ff2e2e;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    button:hover {
      background-color: #e60000;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>💪 Tính BMI & TDEE</h2>
    <form action="bmi_result.php" method="post">
      <input type="number" name="weight" placeholder="Cân nặng (kg)" step="0.1" required>
      <input type="number" name="height" placeholder="Chiều cao (cm)" step="0.1" required>
      <input type="number" name="age" placeholder="Tuổi" required>
      <select name="gender" required>
        <option value="">Giới tính</option>
        <option value="male">Nam</option>
        <option value="female">Nữ</option>
      </select>
      <select name="activity" required>
        <option value="">Mức độ vận động</option>
        <option value="1.2">Ít vận động</option>
        <option value="1.55">Vận động trung bình</option>
        <option value="1.9">Vận động nhiều</option>
      </select>
      <button type="submit">🔥 Tính ngay</button>
    </form>
  </div>
</body>
</html>
