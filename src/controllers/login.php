<?php
session_start();
require '../../config.php'; // Include the config file
header("Content-Type: application/json");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the database connection is initialized
if (!isset($pdo)) {
    error_log("Database connection not initialized.");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection error."]);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

// Retrieve username and password from the POST data
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

// Check if username and password are provided
if (empty($username)) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Username is required."]);
    exit;
}
if (empty($password)) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Password is required."]);
    exit;
}

try {
    // Prepare SQL statement to join Users and student_info tables
    $stmt = $pdo->prepare("
        SELECT 
            u.user_id, u.username, u.password, u.role, 
            si.first_name, si.last_name 
        FROM Users u
        LEFT JOIN student_info si ON u.user_id = si.user_id
        WHERE u.username = ?
    ");
    $stmt->execute([$username]);

    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true); // Regenerate session ID for security
        
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
        
            http_response_code(200); // OK
            echo json_encode([
                "status" => "success", 
                "message" => "Login successful.",
                "username" => $row['username'],
                "role" => $row['role'],
                "first_name" => $row['first_name'],
                "last_name" => $row['last_name']
            ]);
            exit;
        }        
    }

    // Authentication failed
    http_response_code(401); // Unauthorized
    echo json_encode(["status" => "error", "message" => "Invalid username or password."]);
    exit;

} catch (Exception $e) {
    error_log("Error in login.php: " . $e->getMessage()); // Log the error
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "An internal error occurred. Please contact support."]);
    exit;
}
?>