<?php
require_once './includes/config.php';

// Lọc
$category = $_GET['category'] ?? '';
$goal = $_GET['goal'] ?? '';
$difficulty = $_GET['difficulty'] ?? '';
$duration = $_GET['duration'] ?? '';
$equipment = $_GET['equipment'] ?? '';
$intensity = $_GET['intensity'] ?? '';
$gender = $_GET['gender'] ?? '';

$sql = "SELECT * FROM exercises WHERE 1";
if ($category) $sql .= " AND category = '" . mysqli_real_escape_string($conn, $category) . "'";
if ($goal) $sql .= " AND goal = '" . mysqli_real_escape_string($conn, $goal) . "'";
if ($difficulty) $sql .= " AND difficulty = '" . mysqli_real_escape_string($conn, $difficulty) . "'";
if ($duration) $sql .= " AND duration = '" . mysqli_real_escape_string($conn, $duration) . "'";
if ($equipment) $sql .= " AND equipment = '" . mysqli_real_escape_string($conn, $equipment) . "'";
if ($intensity) $sql .= " AND intensity = '" . mysqli_real_escape_string($conn, $intensity) . "'";
if ($gender) $sql .= " AND gender = '" . mysqli_real_escape_string($conn, $gender) . "'";

$result = mysqli_query($conn, $sql);

// Lấy danh sách video upload mới
$sqlVideos = "SELECT * FROM videos ORDER BY uploaded_at DESC";
$resultVideos = mysqli_query($conn, $sqlVideos);

// Gửi đánh giá
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['exercise_id'], $_POST['rating'])) {
    $exercise_id = intval($_POST['exercise_id']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment'] ?? '');

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO exercise_ratings (exercise_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $exercise_id, $rating, $comment);
        $stmt->execute();
        header("Location: index.php?" . http_build_query([
            'category' => $category,
            'goal' => $goal,
            'difficulty' => $difficulty,
            'duration' => $duration,
            'equipment' => $equipment,
            'intensity' => $intensity,
            'gender' => $gender
        ]) . "#exercise_$exercise_id");
        exit;
    } else {
        $error = "Đánh giá không hợp lệ.";
    }
}

function getRatings($conn, $exercise_id) {
    $stmt = $conn->prepare("SELECT rating, comment, created_at FROM exercise_ratings WHERE exercise_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();
    return $stmt->get_result();
}

function renderStars($count) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $count) {
            $stars .= '<i class="bi bi-star-fill text-warning"></i> ';
        } else {
            $stars .= '<i class="bi bi-star text-secondary"></i> ';
        }
    }
    return $stars;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bài tập Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        html {
            scroll-behavior: smooth;
        }
        .exercise-card img {
            max-height: 220px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .rating-badge {
            font-size: 0.9rem;
        }
        .rating-form textarea {
            resize: vertical;
        }
    </style>
    <link rel="stylesheet" href="./assets/styles-extra.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a href="/index.php"><h1 class="dumbbell-title">🏋️‍♂️ PT GYM</h1></a>
    </div>
</nav>

<div class="container">

    <h1 class="mb-4">📋 Danh sách bài tập</h1>

    <!-- FORM LỌC -->
    <form method="GET" class="row g-3 align-items-center mb-4">
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">-- Nhóm cơ --</option>
                <?php
                $groups = ['ngực','lưng','chân','tay','bụng','vai','toàn thân'];
                foreach ($groups as $g) {
                    $selected = ($category == $g) ? 'selected' : '';
                    echo "<option value=\"$g\" $selected>".ucfirst($g)."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="goal" class="form-select">
                <option value="">-- Mục tiêu --</option>
                <?php
                $goals = ['tăng cơ', 'giảm mỡ', 'giữ dáng'];
                foreach ($goals as $gl) {
                    $selected = ($goal == $gl) ? 'selected' : '';
                    echo "<option value=\"$gl\" $selected>".ucfirst($gl)."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="difficulty" class="form-select">
                <option value="">-- Độ khó --</option>
                <?php
                $difficulties = ['dễ', 'trung bình', 'khó'];
                foreach ($difficulties as $diff) {
                    $selected = ($difficulty == $diff) ? 'selected' : '';
                    echo "<option value=\"$diff\" $selected>".ucfirst($diff)."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="duration" class="form-select">
                <option value="">-- Thời gian tập --</option>
                <?php
                $durations = ['under_10' => 'Dưới 10 phút', '10_30' => '10 - 30 phút', 'over_30' => 'Trên 30 phút'];
                foreach ($durations as $key => $label) {
                    $selected = ($duration == $key) ? 'selected' : '';
                    echo "<option value=\"$key\" $selected>$label</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="equipment" class="form-select">
                <option value="">-- Loại dụng cụ --</option>
                <?php
                $equipments = ['no_equipment' => 'Không dụng cụ', 'dumbbell' => 'Tạ tay', 'machine' => 'Máy tập', 'band' => 'Dây kháng lực'];
                foreach ($equipments as $key => $label) {
                    $selected = ($equipment == $key) ? 'selected' : '';
                    echo "<option value=\"$key\" $selected>$label</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="intensity" class="form-select">
                <option value="">-- Cường độ --</option>
                <?php
                $intensities = ['nhẹ', 'vừa', 'nặng'];
                foreach ($intensities as $int) {
                    $selected = ($intensity == $int) ? 'selected' : '';
                    echo "<option value=\"$int\" $selected>".ucfirst($int)."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <select name="gender" class="form-select">
                <option value="">-- Phù hợp với --</option>
                <?php
                $genders = ['nam', 'nữ', 'cả hai'];
                foreach ($genders as $gen) {
                    $selected = ($gender == $gen) ? 'selected' : '';
                    echo "<option value=\"$gen\" $selected>".ucfirst($gen)."</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-1 d-grid">
            <button type="submit" class="btn btn-primary">🔍 Lọc</button>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- DANH SÁCH BÀI TẬP -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="col">
            <div class="card exercise-card h-100" id="exercise_<?= $row['id'] ?>">
                <img src="<?= htmlspecialchars($row['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['title']) ?>" />
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                    <p class="card-text flex-grow-1"><?= htmlspecialchars($row['description']) ?></p>
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="<?= htmlspecialchars($row['video_url']) ?>" title="<?= htmlspecialchars($row['title']) ?>" allowfullscreen></iframe>
                    </div>
                    <small class="text-muted">Độ khó: <?= htmlspecialchars($row['difficulty']) ?> | Thời gian: <?= htmlspecialchars($row['duration']) ?></small>
                    <hr />
                    <?php
                    $ratings = getRatings($conn, $row['id']);
                    $countRatings = 0;
                    $totalStars = 0;
                    while ($ratingRow = $ratings->fetch_assoc()) {
                        $countRatings++;
                        $totalStars += $ratingRow['rating'];
                    }
                    $avgStars = $countRatings ? round($totalStars / $countRatings) : 0;
                    ?>
                    <div>
                        <?= renderStars($avgStars) ?>
                        <span class="badge bg-secondary rating-badge"><?= $countRatings ?> đánh giá</span>
                    </div>
                    <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="collapse" data-bs-target="#ratingForm_<?= $row['id'] ?>">Đánh giá bài tập</button>
                    <div class="collapse rating-form mt-2" id="ratingForm_<?= $row['id'] ?>">
                        <form method="POST" action="#exercise_<?= $row['id'] ?>">
                            <input type="hidden" name="exercise_id" value="<?= $row['id'] ?>" />
                            <div class="mb-2">
                                <label for="rating_<?= $row['id'] ?>" class="form-label">Số sao (1-5):</label>
                                <input type="number" min="1" max="5" name="rating" id="rating_<?= $row['id'] ?>" class="form-control" required />
                            </div>
                            <div class="mb-2">
                                <label for="comment_<?= $row['id'] ?>" class="form-label">Bình luận (tuỳ chọn):</label>
                                <textarea name="comment" id="comment_<?= $row['id'] ?>" rows="3" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm">Gửi đánh giá</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>

    <hr />

    <h2 class="mb-3">📹 Video tập luyện mới nhất</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
        <?php while ($video = mysqli_fetch_assoc($resultVideos)): ?>
        <div class="col">
            <div class="card h-100">
                <div class="ratio ratio-16x9">
                    <iframe src="<?= htmlspecialchars($video['video_url']) ?>" title="<?= htmlspecialchars($video['title']) ?>" allowfullscreen></iframe>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($video['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($video['description']) ?></p>
                    <small class="text-muted">Đăng ngày: <?= date('d/m/Y', strtotime($video['uploaded_at'])) ?></small>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
