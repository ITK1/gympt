<?php
$conn = new mysqli("localhost", "root", "", "ptgym1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>