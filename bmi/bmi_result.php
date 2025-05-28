<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả BMI & TDEE</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url('../assets/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: white;
    }
    .result-container {
      max-width: 400px;
      margin: 80px auto;
      background-color: rgba(0, 0, 0, 0.85);
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px #ff2e2e;
      text-align: center;
    }
    h2 {
      color: #ff2e2e;
      margin-bottom: 25px;
    }
    p {
      font-size: 18px;
      margin: 12px 0;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 18px;
      background-color: #ff2e2e;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      transition: background-color 0.3s;
    }
    a:hover {
      background-color: #e60000;
    }
  </style>
</head>
<body>
  <div class="result-container">
    <?php
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $activity = $_POST['activity'];

    $bmi = $weight / pow($height / 100, 2);
    if ($gender === 'male') {
      $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
    } else {
      $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
    }
    $tdee = $bmr * (float)$activity;
    ?>

    <h2>💪 Kết quả của bạn</h2>
    <p><strong>BMI:</strong> <?= round($bmi, 1) ?></p>
    <p><strong>TDEE:</strong> <?= round($tdee) ?> calo/ngày</p>
    <a href="bmi.php">🔁 Tính lại</a>
  </div>
</body>
</html>
