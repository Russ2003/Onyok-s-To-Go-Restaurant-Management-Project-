<?php
include 'db_connect.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Start session and store user data
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;

            // Redirect to index.html
            header("Location: index.html");
            exit(); // Ensure no further code is executed after redirect
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>
