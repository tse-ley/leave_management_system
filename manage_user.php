<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $query = "INSERT INTO users (username, email, password, first_name, last_name, is_admin) 
              VALUES (?, ?, ?, ?, ?, ?)";
   $stmt = $conn->prepare("SELECT id, username, email, first_name, last_name, is_admin FROM users WHERE id = ?");
   $stmt = $conn->prepare("
   SELECT lr.start_date, u.username, u.email, u.first_name, u.last_name, u.is_admin 
   FROM leave_requests lr
   JOIN users u ON lr.user_id = u.id
   WHERE lr.user_id = ? 
   ORDER BY lr.created_at DESC
");
    $stmt->bind_param("sssssi", $username, $email, $password, $first_name, $last_name, $is_admin);
    $stmt->execute();
}

$query = "SELECT id, username, email, first_name, last_name, is_admin FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Users</h1>
    <h2>Add New User</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="is_admin">
            <input type="checkbox" id="is_admin" name="is_admin"> Is Admin
        </label>

        <button type="submit">Add User</button>
    </form>

    <h2>User List</h2>
    <table>
        <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Full Name</th>
        <th>Admin</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>

    <td data-label="Username"><?= htmlspecialchars($row['username']) ?></td>
    <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
    <td data-label="Full Name"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
    <td data-label="Admin"><?= $row['is_admin'] ? 'Yes' : 'No' ?></td>
</tr>
        <?php endwhile; ?>
    </table>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>