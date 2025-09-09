<?php
session_start(); // Start the session to access session variables

if (!isset($_SESSION['user_id'])) {
    // If not logged in, return a message prompting the user to log in
    header("Location: login.html");
    exit();
}

// If logged in, display a welcome message
echo 'Hello, <strong>' . htmlspecialchars($_SESSION['username']) . '</strong>! You are logged in.';
?>
