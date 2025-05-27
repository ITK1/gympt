<?php
require './includes/config.php';
$id = $_GET['id'];
$conn->query("UPDATE trainers SET approved = 1 WHERE id = $id");
header("Location: admin_pt_approval.php");
?>
