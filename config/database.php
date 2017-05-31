<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "progesh";
 
// Create connection
$conn = new mysqli($server, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>