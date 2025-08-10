<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Default for XAMPP
$password = ""; // Default is empty for XAMPP
$dbname = "coffeehouse_crm"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer data
$sql = "SELECT id, name, age, phone, visits, amount_spent FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - Coffeehouse CRM</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-container {
            max-width: 90%;
            margin: 40px auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ccc;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f0f0f0;
        }
    </style>
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

<div class="dashboard-container">
    <h2>Customer Dashboard</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Phone</th>
                <th>Visits</th>
                <th>Amount Spent ($)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $amountFormatted = number_format($row['amount_spent'], 2);
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['phone']}</td>
                            <td>{$row['visits']}</td>
                            <td>\${$amountFormatted}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No customers found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$conn->close();
?>
