<?php
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "admin"){
    header("location: ../login.php");
    exit;
}
