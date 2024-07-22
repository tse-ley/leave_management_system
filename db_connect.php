<?php
$host = 'localhost';
$db_name = 'leave_management_system';
$username = 'root';
$password = '';  

try {
    $conn = new mysqli($host, $username, $password, $db_name);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");
} catch(Exception $e) {
    die("ERROR: Could not connect to the database. " . $e->getMessage());
}
?>