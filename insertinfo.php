<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "coffeehouse_crm";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<div class='container error'>Connection failed: " . $conn->connect_error . "</div>");
}

// Get form data
$name = $_POST['name'];
$age = $_POST['age'];
$phone = $_POST['phone'];
$visits = $_POST['visits'];
$amount_spent = $_POST['amount_spent'];

$sql = "INSERT INTO customers (name, age, phone, visits, amount_spent) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sisis", $name, $age, $phone, $visits, $amount_spent);

$message = "";
$success = false;

if ($stmt->execute()) {
    $message = "✅ Customer added successfully!";
    $success = true;
} else {
    $message = "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Added - Coffeehouse CRM</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success {
            color: #2e7d32;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .error {
            color: #d32f2f;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .actions a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .actions a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="container <?= $success ? 'success' : 'error' ?>">
    <?= $message ?>
    <div class="actions">
        <a href="addinfo.php">➕ Add Another</a>
        <a href="custdash.php">📊 Dashboard</a>
    </div>
</div>

</body>
</html>
