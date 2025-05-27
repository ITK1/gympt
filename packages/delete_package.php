<?php
require_once './includes/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM packages WHERE id = $id");
header("Location: packages.php");
?>