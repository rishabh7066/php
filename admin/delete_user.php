<?php
require_once "auth.php";

$id = $_GET['id'];
mysqli_query($link, "DELETE FROM users WHERE id=$id");

header("location: users.php");
exit;
