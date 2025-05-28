<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) exit;

$my_id = $_SESSION['user_id'];
$chat_with = intval($_GET['chat_with'] ?? 0);

$stmt = $conn->prepare("SELECT * FROM messages 
    WHERE (from_user = ? AND to_user = ?) OR (from_user = ? AND to_user = ?)
    ORDER BY timestamp ASC");
$stmt->bind_param("iiii", $my_id, $chat_with, $chat_with, $my_id);
$stmt->execute();
$result = $stmt->get_result();

while ($msg = $result->fetch_assoc()) {
    $fromMe = $msg['from_user'] == $my_id;
    echo '<div class="mb-2 '.($fromMe ? 'text-end' : 'text-start').'">';
    echo '<span class="badge '.($fromMe ? 'bg-primary' : 'bg-secondary').'">'.htmlspecialchars($msg['message']).'</span>';
    echo '</div>';
}
