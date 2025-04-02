<?php
session_start();
header("Content-Type: application/json");

// Debugging: Log session data
error_log("Session in getUser.php: " . print_r($_SESSION, true));

if (isset($_SESSION['username'])) {
    echo json_encode([
        "status" => "success",
        "username" => $_SESSION['username'],
        "full_name" => $_SESSION['first_name'] . " " . $_SESSION['last_name']
    ]);
} else {
    echo json_encode(["status" => "error", "username" => "Guest", "full_name" => "Unknown"]);
}
?>
