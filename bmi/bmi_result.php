
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

echo "<h2>Kết quả:</h2>";
echo "<p>BMI: " . round($bmi, 1) . "</p>";
echo "<p>TDEE: " . round($tdee) . " calo/ngày</p>";
?>
