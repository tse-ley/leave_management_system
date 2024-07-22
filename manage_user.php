<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../auth/login.php');
    exit();
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_user'])) {
    $user_id_to_delete = $_POST['delete_user'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $user_id_to_delete);
    if ($delete_stmt->execute()) {
        $delete_message = "User successfully deleted.";
    } else {
        $delete_error = "Error deleting user.";
    }
}

// Handle user addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    $query = "INSERT INTO users (username, email, password, first_name, last_name, is_admin) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $username, $email, $password, $first_name, $last_name, $is_admin);
    if ($stmt->execute()) {
        $add_message = "User successfully added.";
    } else {
        $add_error = "Error adding user.";
    }
}

// Fetch user list
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
    <script>
        function confirmDelete(username) {
            return confirm("Are you sure you want to delete user: " + username + "?");
        }
    </script>
</head>
<body>
    <h1>Manage Users</h1>

    <?php
    if (isset($delete_message)) echo "<p class='success'>$delete_message</p>";
    if (isset($delete_error)) echo "<p class='error'>$delete_error</p>";
    if (isset($add_message)) echo "<p class='success'>$add_message</p>";
    if (isset($add_error)) echo "<p class='error'>$add_error</p>";
    ?>

    <h2>Add New User</h2>
    <form method="POST">
        <input type="hidden" name="add_user" value="1">
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
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="Username"><?= htmlspecialchars($row['username']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Full Name"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td data-label="Admin"><?= $row['is_admin'] ? 'Yes' : 'No' ?></td>
                <td data-label="Action">
                    <form method="POST" onsubmit="return confirmDelete('<?= htmlspecialchars($row['username']) ?>')">
                        <input type="hidden" name="delete_user" value="<?= $row['id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>