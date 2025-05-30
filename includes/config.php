<?php
// config.php
define('ROLE_ADMIN', 'admin');
define('ROLE_PT', 'pt');
define('ROLE_MEMBER', 'member');

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'ptgym1';
$conn = new mysqli($host,$user,$pass,$db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>  