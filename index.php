<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="container">
        <h1>Welcome To The Leave Management System</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['is_admin']): ?>
                <p>Welcome, Admin!</p>
                <a href="admin_dashboard.php">Go to Admin Dashboard</a>
            <?php else: ?>
                <p>Welcome, Employee!</p>
                <a href="employee_dashboard.php">Go to Employee Dashboard</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <p><b>Please login or register to continue.</p>
            <a href="login.php">Login</a>
            <a href="register.php">Register as Employee</a>
            <a href="register_admin.php">Register as Admin</a>
        <?php endif; ?>
    </div>
</body>
</html>