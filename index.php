<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>InternTrack</h1>
        <p>Track your internship applications in one place.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php" class="btn">Go to Dashboard</a>
            <a href="logout.php" class="btn secondary">Logout</a>
        <?php else: ?>
            <a href="register.php" class="btn">Register</a>
            <a href="login.php" class="btn secondary">Login</a>
        <?php endif; ?>
    </div>
</body>
</html>