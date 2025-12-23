<?php
require_once "auth.php";

$id = $_GET['id'];
mysqli_query($link, "DELETE FROM posts WHERE id=$id");

header("location: posts.php");
exit;
