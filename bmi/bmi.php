
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Tính BMI & TDEE</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Tính BMI & TDEE</h2>
    <form action="bmi_result.php" method="post">
      <input type="number" name="weight" placeholder="Cân nặng (kg)" required>
      <input type="number" name="height" placeholder="Chiều cao (cm)" required>
      <input type="number" name="age" placeholder="Tuổi" required>
      <select name="gender">
        <option value="male">Nam</option>
        <option value="female">Nữ</option>
      </select>
      <select name="activity">
        <option value="1.2">Ít vận động</option>
        <option value="1.55">Vận động trung bình</option>
        <option value="1.9">Vận động nhiều</option>
      </select>
      <button type="submit">Tính</button>
    </form>
  </div>
</body>
</html>
