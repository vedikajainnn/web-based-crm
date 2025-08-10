<?php
session_start();
require 'db.php'; // Include your database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // If user exists, verify password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $stored_password);
        $stmt->fetch();

        // Check if entered password matches stored password (Plain Text)
        if ($password === $stored_password) { 
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $id;

            // Redirect to addinfo.php without any message
            header("Location: addinfo.php");
            exit();
        } else {
            header("Location: login.php?error=Invalid credentials! Try again."); // Incorrect password
            exit();
        }
    } else {
        header("Location: login.php?error=Username not found!"); // Username not found
        exit();
    }

    $stmt->close();
}
$conn->close();
?>
