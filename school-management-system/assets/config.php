<?php
$hostname = "localhost";
$username = "root";
$password = "Terkperson@830"; // Ensure this matches exactly what you use to log in to phpMyAdmin
$database = "_sms";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>