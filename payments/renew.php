<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}
$userId = $_SESSION['user_id'];
header("Location: create_momo_qr.php?type=renew&user_id=$userId");
exit;
