<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "expense_tracker";

// Create connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
