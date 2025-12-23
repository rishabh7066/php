<?php
// Database credentials for the sandbox environment
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', ''); // No password for root in this sandbox setup
define('DB_NAME', 'blog');

// Attempt to connect to MySQL database
 $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Start session for user authentication
session_start();
?>
