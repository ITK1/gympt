<?php
// Thư mục lưu video
$targetDir = "uploads/";

// Tạo thư mục nếu chưa có
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $fileName = basename($_FILES["video"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Kiểm tra định dạng video cho phép (ví dụ mp4, mov, avi)
        $allowedTypes = ['mp4', 'mov', 'avi', 'mkv', 'webm'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes)) {
            $message = "Chỉ cho phép định dạng video: " . implode(', ', $allowedTypes);
        } else {
            // Di chuyển file lên server
            if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFilePath)) {
                $videoURL = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/" . $targetFilePath;
                $message = "Upload thành công!<br>Link video: <a href='$videoURL' target='_blank'>$videoURL</a>";
            } else {
                $message = "Lỗi khi tải video lên server.";
            }
        }
    } else {
        $message = "Chưa chọn video hoặc video bị lỗi.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Upload Video Bài Tập</title>
</head>
<body>
    <h2>Upload Video cho bài tập</h2>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label>Chọn video (mp4, mov, avi, mkv, webm):</label><br>
        <input type="file" name="video" accept="video/*" required /><br><br>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
