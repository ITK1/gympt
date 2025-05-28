<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Giới hạn chỉ member chat với PT
if ($role !== 'member') {
    die('Chỉ thành viên mới được chat với PT.');
}

$pt_id = intval($_GET['pt_id'] ?? 0);

if ($pt_id === 0) {
    die('Chưa chọn PT.');
}

// Lấy thông tin PT
$stmt = $conn->prepare("SELECT name FROM trainers WHERE id = ?");
$stmt->bind_param("i", $pt_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die('PT không tồn tại.');
}
$pt = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chat với PT <?= htmlspecialchars($pt['name']) ?></title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
#chat-box { border:1px solid #ccc; height:300px; overflow-y:scroll; padding:10px; }
.message { margin-bottom: 10px; }
.message.me { text-align:right; color: blue; }
.message.pt { text-align:left; color: green; }
</style>
</head>
<body>
<h2>Chat với PT <?= htmlspecialchars($pt['name']) ?></h2>

<div id="chat-box"></div>

<form id="chat-form">
  <input type="hidden" name="receiver_id" value="<?= $pt_id ?>">
  <input type="text" name="message" id="message" required autocomplete="off" placeholder="Nhập tin nhắn...">
  <button type="submit">Gửi</button>
</form>

<script>
function loadMessages() {
    $.getJSON('get_messages.php', { chat_with: <?= $pt_id ?> }, function(data) {
        let html = '';
        data.forEach(function(msg) {
            const cls = msg.sender_id == <?= $user_id ?> ? 'me' : 'pt';
            html += `<div class="message ${cls}">${msg.message} <br><small>${msg.created_at}</small></div>`;
        });
        $('#chat-box').html(html);
        $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
    });
}

$('#chat-form').submit(function(e) {
    e.preventDefault();
    $.post('send_message.php', $(this).serialize(), function(res) {
        if (res.status === 'success') {
            $('#message').val('');
            loadMessages();
        } else {
            alert(res.message);
        }
    }, 'json');
});

loadMessages();
setInterval(loadMessages, 3000);
</script>

</body>
</html>
