<?php
$host = "localhost";
$username = "root";
$password = "Terkperson@830";
$dbname = "_sms"; // Must match the name in phpMyAdmin

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Database connection failed"]));
}
?>