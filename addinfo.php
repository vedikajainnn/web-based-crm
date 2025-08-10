<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer - Coffeehouse CRM</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label, input {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }
        input, button {
            padding: 8px;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function validateForm() {
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^[0-9]{10}$/;

            if (!phoneRegex.test(phone)) {
                alert("Phone number must be exactly 10 digits.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
<header>
    <nav>
        <div class="logo">Coffeehouse CRM</div>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="addinfo.php">Add Customer</a></li>
            <li><a href="custdash.php">Customer Dashboard</a></li>
            <li><a href="custanalysis.php">Analysis</a></li>
        </ul>
    </nav>
</header>

<div class="form-container">
    <h2>Add Customer Information</h2>
    <form action="insertinfo.php" method="POST" onsubmit="return validateForm()">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="1" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" maxlength="10" required>

        <label for="visits">Number of Visits:</label>
        <input type="number" id="visits" name="visits" min="0" required>

        <label for="amount_spent">Amount Spent ($):</label>
        <input type="number" id="amount_spent" name="amount_spent" step="0.01" min="0" required>

        <button type="submit">Add Customer</button>
    </form>
</div>

</body>
</html>
