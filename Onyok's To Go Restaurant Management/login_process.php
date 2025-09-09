<?php
session_start();

// Replace with your database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onyoks_to_go";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture user input from the form
$userInput = $_POST['username_or_email'];
$userPassword = $_POST['password'];

// Query to find the user
$sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userInput, $userInput);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Verify password
    if (password_verify($userPassword, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Clear the cart session to reset it for logged-in users
        unset($_SESSION['cart']);

        header("Location: index.php");
        exit();
    } else {
        // Password is incorrect
        header("Location: login.html?error=invalid_password");
        exit();
    }
} else {
    // Username or email not found
    header("Location: login.html?error=user_not_found");
    exit();
}
?>
