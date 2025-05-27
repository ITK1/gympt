<!-- add_schedule.php -->
<?php
require_once '../includes/config.php';
$members = $conn->query("SELECT id, name FROM members");
$trainers = $conn->query("SELECT id, name FROM trainers");
?>
<main>
<h2>Thêm lịch tập</h2>
<form method="POST">
  <select name="member_id">
    <?php while ($m = $members->fetch_assoc()): ?>
      <option value="<?= $m['id'] ?>"><?= $m['name'] ?></option>
    <?php endwhile; ?>
  </select>
  <select name="trainer_id">
    <?php while ($t = $trainers->fetch_assoc()): ?>
      <option value="<?= $t['id'] ?>"><?= $t['name'] ?></option>
    <?php endwhile; ?>
  </select>
  <input type="date" name="date">
  <input type="time" name="time">
  <button type="submit">Thêm</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $conn->prepare("INSERT INTO schedules (member_id, trainer_id, date, time) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("iiss", $_POST['member_id'], $_POST['trainer_id'], $_POST['date'], $_POST['time']);
  $stmt->execute();
  header("Location: schedule.php");
}
?>
</main>