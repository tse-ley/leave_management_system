<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../auth/login.php');
    exit();
}

$query = "SELECT lr.*, u.first_name, u.last_name FROM leave_requests lr
          JOIN users u ON lr.user_id = u.id
          ORDER BY lr.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>

    <a href="manage_user.php">Manage Users</a>
    <h2>All Leave Requests</h2>
    <table>
        <tr>
            <th>Employee</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Submitted On</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td data-label="Employee"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
            <td data-label="Start Date"><?= $row['start_date'] ?></td>
            <td data-label="End Date"><?= $row['end_date'] ?></td>
            <td data-label="Reason"><?= htmlspecialchars($row['reason']) ?></td>
            <td data-label="Status"><?= ucfirst($row['status']) ?></td>
            <td data-label="Submitted On"><?= $row['created_at'] ?></td>
            <td data-label="Action">
                <?php if ($row['status'] == 'pending'): ?>
                <a href="manage_request.php?action=approve&id=<?= $row['id'] ?>">Approve</a>
                <a href="manage_request.php?action=reject&id=<?= $row['id'] ?>">Reject</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="logout.php">Logout</a>
</body>
</html>