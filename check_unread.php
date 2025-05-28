<?php
session_start();
require_once './includes/config.php';

$to_user = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT COUNT(*) as unread_count FROM messages WHERE to_user = ? AND is_read = 0");
$stmt->bind_param("i", $to_user);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['unread' => $result['unread_count']]);
