<?php
require_once './includes/config.php';

$today = date('Y-m-d');

// Kiểm tra user hết hạn 30 ngày sau `last_payment`
$conn->query("UPDATE users SET status = 'expired' WHERE last_payment IS NOT NULL AND DATEDIFF('$today', last_payment) >= 30");

// Tuỳ chỉnh nếu muốn khóa sau 35 ngày
$conn->query("UPDATE users SET status = 'banned' WHERE last_payment IS NOT NULL AND DATEDIFF('$today', last_payment) >= 35");
