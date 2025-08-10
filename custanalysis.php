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
$sql = "SELECT name, visits, amount_spent FROM customers";
$result = $conn->query($sql);

// Store data for graph
$categories = ["Gold" => 0, "Silver" => 0, "Bronze" => 0];
$churn_risk = ["Low" => 0, "Medium" => 0, "High" => 0];

$customer_data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $visits = $row['visits'];
        $amount_spent = $row['amount_spent'];

        // Calculate amount spent per visit ratio
        $ratio = ($visits > 0) ? ($amount_spent / $visits) : 0;

        // Determine category
        if ($ratio > 1000) {
            $category = "Gold";
            $churn = "Low";
        } elseif ($ratio >= 400) {
            $category = "Silver";
            $churn = "Medium";
        } else {
            $category = "Bronze";
            $churn = "High";
        }

        // Store data for display
        $customer_data[] = [
            "name" => $name,
            "category" => $category,
            "churn" => $churn
        ];

        // Update graph data
        $categories[$category]++;
        $churn_risk[$churn]++;
    }
} else {
    echo "<p>No customer data available.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Analysis - Coffeehouse CRM</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-wrapper {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            margin-top: 30px;
            margin-bottom: 40px;
        }

        .chart-box {
            text-align: center;
        }

        canvas {
            max-width: 350px;
            max-height: 350px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background-color:#5c3a21;
        }

        .analysis-container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
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

    <div class="analysis-container">
        <h2>Customer Value & Churn Risk Analysis</h2>

        <table>
            <tr>
                <th>Customer Name</th>
                <th>Value Category</th>
                <th>Churn Risk</th>
            </tr>
            <?php foreach ($customer_data as $customer) : ?>
                <tr>
                    <td><?php echo $customer["name"]; ?></td>
                    <td><?php echo $customer["category"]; ?></td>
                    <td><?php echo $customer["churn"]; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="chart-wrapper">
            <div class="chart-box">
                <h3>Customer Value Distribution</h3>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="chart-box">
                <h3>Churn Risk Distribution</h3>
                <canvas id="churnChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const categoryData = {
            labels: ["Gold", "Silver", "Bronze"],
            datasets: [{
                label: "Customer Value Categories",
                data: [<?php echo $categories["Gold"]; ?>, <?php echo $categories["Silver"]; ?>, <?php echo $categories["Bronze"]; ?>],
                backgroundColor: ["#b8860b", "#c0c0c0", "#cd7f32"],
                borderColor: "#654321",
                borderWidth: 1
            }]
        };

        const churnData = {
            labels: ["Low", "Medium", "High"],
            datasets: [{
                label: "Churn Risk Levels",
                data: [<?php echo $churn_risk["Low"]; ?>, <?php echo $churn_risk["Medium"]; ?>, <?php echo $churn_risk["High"]; ?>],
                backgroundColor: ["#b8860b", "#c0c0c0", "#cd7f32"],
                borderColor: "#654321",
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById("categoryChart"), {
            type: "pie",
            data: categoryData
        });

        new Chart(document.getElementById("churnChart"), {
            type: "pie",
            data: churnData
        });
    </script>
</body>
</html>
