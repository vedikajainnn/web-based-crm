<?php
session_start();
$alreadyLoggedIn = false;

if (isset($_SESSION['username'])) {
    $alreadyLoggedIn = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffeehouse CRM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav class="hey">
        <div class="logo">Coffeehouse CRM</div>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Logout link always available -->
        </ul>
    </nav>

    <div class="login-container">
        <h2>EMPLOYEE LOGIN</h2>

        <?php
        if (isset($_GET['error'])) {
            echo "<p style='color: red;'>Invalid credentials! Try again.</p>";
        }

        if ($alreadyLoggedIn) {
            echo "<p style='color: green;'>You're already signed in as <strong>{$_SESSION['username']}</strong>. <a href='logout.php'>Logout?</a></p>";
        }
        ?>

        <form action="authenticate.php" method="POST" <?php if ($alreadyLoggedIn) echo "style='pointer-events: none; opacity: 0.6;'"; ?>>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required <?php if ($alreadyLoggedIn) echo "disabled"; ?>>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required <?php if ($alreadyLoggedIn) echo "disabled"; ?>>

            <br><br>
            <button type="submit" <?php if ($alreadyLoggedIn) echo "disabled"; ?>>Login</button>
        </form>
    </div>

    <script>
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('message')) {
                alert(urlParams.get('message')); // shows logout message
            }

            <?php if ($alreadyLoggedIn): ?>
                alert("You're already signed in.");
            <?php endif; ?>
        };
    </script>

</body>
</html>
