<?php
require_once "auth.php";

$id = $_GET['id'];
mysqli_query($link, "UPDATE users SET status='blocked' WHERE id=$id");

header("location: users.php");
exit;
