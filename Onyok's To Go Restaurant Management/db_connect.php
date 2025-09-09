<?php
$servername = "localhost";
$username = "root";
$password = ""; // Change to your database password if necessary
$dbname = "onyoks_to_go";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
