<?php
require './includes/config.php';
$data = $conn->query("SELECT * FROM trainers WHERE approved = 0");
while ($pt = $data->fetch_assoc()) {
  echo "<div><img src='{$pt['photo']}' width='100'> {$pt['name']} - {$pt['specialization']} ";
  echo "<a href='approve_pt.php?id={$pt['id']}'>Duyệt</a></div>";
}
?>