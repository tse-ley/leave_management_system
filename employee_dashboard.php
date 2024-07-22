<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin']) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM leave_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .status-pending { color: #FFA500; }
        .status-approved { color: #008000; }
        .status-rejected { color: #FF0000; }
        .decision { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Dashboard</h1>
        <a href="submit_request.php" class="btn">Submit Leave Request</a>
        <a href="logout.php">Logout</a>
        <h2>Your Leave Requests</h2>
        <table>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Decision</th>
                <th>Submitted On</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td data-label="Start Date"><?= htmlspecialchars($row['start_date']) ?></td>
                <td data-label="End Date"><?= htmlspecialchars($row['end_date']) ?></td>
                <td data-label="Reason"><?= htmlspecialchars($row['reason']) ?></td>
                <td data-label="Status">
                    <span class="status-<?= $row['status'] ?>">
                        <?= ucfirst(htmlspecialchars($row['status'])) ?>
                    </span>
                </td>
                <td data-label="Decision" class="decision">
                    <?php
                    switch($row['status']) {
                        case 'approved':
                            echo "Your request has been approved!";
                            break;
                        case 'rejected':
                            echo "Your request has been rejected.";
                            break;
                        default:
                            echo "Pending decision";
                    }
                    ?>
                </td>
                <td data-label="Submitted On"><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>