
<?php
require_once './includes/config.php';

// Lấy danh sách bài tập
$exercises = $conn->query("SELECT * FROM exercises ORDER BY category, id");

// Xử lý gửi đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exercise_id'], $_POST['rating'])) {
    $exercise_id = intval($_POST['exercise_id']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment'] ?? '');

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO exercise_ratings (exercise_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $exercise_id, $rating, $comment);
        $stmt->execute();
        header("Location: exercises.php#exercise_$exercise_id");
        exit;
    } else {
        $error = "Đánh giá không hợp lệ.";
    }
}

// Lấy đánh giá theo bài tập
function getRatings($conn, $exercise_id) {
    $stmt = $conn->prepare("SELECT rating, comment, created_at FROM exercise_ratings WHERE exercise_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>💪 Gợi ý bài tập</title>
    <link rel="stylesheet" href="./assets/style.css" />
    <style>
        body { max-width: 900px; margin: auto; font-family: Arial, sans-serif; padding: 20px; }
        h2 { margin-top: 40px; border-bottom: 2px solid #333; padding-bottom: 5px; }
        .exercise { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .ratings { margin-top: 10px; font-size: 14px; }
        .rating-item { border-top: 1px solid #eee; padding: 8px 0; }
        .rating-stars { color: #f39c12; }
        form { margin-top: 10px; }
        label { font-weight: bold; }
        textarea { width: 100%; height: 60px; margin: 5px 0 10px; }
        select, button { padding: 5px 10px; margin-top: 5px; }
        .error { color: red; }
    </style>
</head>
<body>

<h1>💪 Gợi ý bài tập cho bạn</h1>

<?php
$current_category = '';
while ($row = $exercises->fetch_assoc()):
    if ($row['category'] !== $current_category) {
        $current_category = $row['category'];
        echo "<h2>" . htmlspecialchars(ucfirst($current_category)) . "</h2>";
    }
?>
    <div class="exercise" id="exercise_<?= $row['id'] ?>">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

        <div class="ratings">
            <strong>Đánh giá:</strong><br>
            <?php
            $ratings = getRatings($conn, $row['id']);
            if ($ratings->num_rows === 0) {
                echo "<em>Chưa có đánh giá nào.</em>";
            } else {
                while ($r = $ratings->fetch_assoc()) {
                    echo '<div class="rating-item">';
                    echo '<span class="rating-stars">' . str_repeat('⭐', $r['rating']) . '</span><br>';
                    if ($r['comment']) echo "<p>" . nl2br(htmlspecialchars($r['comment'])) . "</p>";
                    echo "<small> - " . date('d/m/Y H:i', strtotime($r['created_at'])) . "</small>";
                    echo '</div>';
                }
            }
            ?>
        </div>

        <form method="post">
            <input type="hidden" name="exercise_id" value="<?= $row['id'] ?>" />
            <label for="rating_<?= $row['id'] ?>">Đánh giá của bạn:</label>
            <select name="rating" id="rating_<?= $row['id'] ?>" required>
                <option value="">-- Chọn số sao --</option>
                <?php for ($i=1; $i<=5; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?> ⭐</option>
                <?php endfor; ?>
            </select>
            <br>
            <label for="comment_<?= $row['id'] ?>">Bình luận (tùy chọn):</label>
            <textarea name="comment" id="comment_<?= $row['id'] ?>"></textarea><br>
            <button type="submit">Gửi đánh giá</button>
        </form>
    </div>

<?php endwhile; ?>

<?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

</body>
</html>
