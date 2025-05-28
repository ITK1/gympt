<?php
require_once '../includes/config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Xử lý sửa lịch tập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_schedule'])) {
    $schedule_id = intval($_POST['schedule_id']);
    $member_id = intval($_POST['member_id']);
    $trainer_id = intval($_POST['trainer_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("UPDATE schedules SET member_id = ?, trainer_id = ?, date = ?, time = ? WHERE id = ?");
    $stmt->bind_param("iissi", $member_id, $trainer_id, $date, $time, $schedule_id);
    $stmt->execute();

    header("Location: manage_schedule.php");
    exit;
}

// Xử lý xóa lịch tập
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM schedules WHERE id = $id");
    header("Location: manage_schedule.php");
    exit;
}

// Lấy danh sách lịch tập
$result = $conn->query("SELECT schedules.id, members.name AS member_name, trainers.name AS trainer_name, schedules.date, schedules.time, schedules.member_id, schedules.trainer_id FROM schedules JOIN members ON schedules.member_id = members.id JOIN trainers ON schedules.trainer_id = trainers.id ORDER BY schedules.date, schedules.time");

// Lấy danh sách thành viên và PT để chọn trong form
$members = $conn->query("SELECT id, name FROM members ORDER BY name");
$trainers = $conn->query("SELECT id, name FROM trainers ORDER BY name");

?>
<main>
  <h2>Quản lý lịch tập (Admin)</h2>

  <table border="1" cellpadding="5" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Thành viên</th>
      <th>Huấn luyện viên</th>
      <th>Ngày</th>
      <th>Giờ</th>
      <th>Hành động</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <form method="POST">
      <td><?= $row['id'] ?></td>
      <td>
        <select name="member_id" required>
          <?php
            $members->data_seek(0);
            while ($m = $members->fetch_assoc()) {
                $selected = ($m['id'] == $row['member_id']) ? 'selected' : '';
                echo "<option value='{$m['id']}' $selected>" . htmlspecialchars($m['name']) . "</option>";
            }
          ?>
        </select>
      </td>
      <td>
        <select name="trainer_id" required>
          <?php
            $trainers->data_seek(0);
            while ($t = $trainers->fetch_assoc()) {
                $selected = ($t['id'] == $row['trainer_id']) ? 'selected' : '';
                echo "<option value='{$t['id']}' $selected>" . htmlspecialchars($t['name']) . "</option>";
            }
          ?>
        </select>
      </td>
      <td><input type="date" name="date" value="<?= $row['date'] ?>" required></td>
      <td><input type="time" name="time" value="<?= $row['time'] ?>" required></td>
      <td>
        <input type="hidden" name="schedule_id" value="<?= $row['id'] ?>">
        <button type="submit" name="edit_schedule">Lưu</button>
        <a href="manage_schedule.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xóa lịch tập này?')">Xóa</a>
      </td>
      </form>
    </tr>
    <?php endwhile; ?>
  </table>
</main>
