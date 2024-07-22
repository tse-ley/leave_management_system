<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Server-side validation
    if (strtotime($end_date) < strtotime($start_date)) {
        $error = "End date cannot be earlier than the start date.";
    } else {
        $query = "INSERT INTO leave_requests (user_id, start_date, end_date, reason) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isss", $user_id, $start_date, $end_date, $reason);

        if ($stmt->execute()) {
            $message = "Leave request submitted successfully!";
        } else {
            $error = "Error submitting leave request.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Leave Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script>
        function validateDates() {
            var startDate = new Date(document.getElementById('start_date').value);
            var endDate = new Date(document.getElementById('end_date').value);
            
            if (endDate < startDate) {
                alert("End date cannot be earlier than the start date.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1>Submit Leave Request</h1>
    <?php
    if (isset($message)) echo "<p class='success'>$message</p>";
    if (isset($error)) echo "<p class='error'>$error</p>";
    ?>
    <form method="POST" onsubmit="return validateDates()">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>

        <label for="reason">Reason:</label>
        <textarea id="reason" name="reason" required></textarea>

        <button type="submit">Submit Request</button>
    </form>
    <a href="employee_dashboard.php">Back to Dashboard</a>
</body>
</html>