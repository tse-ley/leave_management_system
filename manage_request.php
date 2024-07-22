<?php
include_once 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: ../auth/login.php');
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'approved' : 'rejected';
        
        $stmt = $conn->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Leave request " . $status . " successfully.";
        } else {
            $_SESSION['message'] = "Error updating leave request.";
        }
        
        $stmt->close();
    }
}

header('Location: admin_dashboard.php');
exit();
?>