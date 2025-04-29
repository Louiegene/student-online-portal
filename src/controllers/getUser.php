<?php
session_start();
header("Content-Type: application/json");

// Debugging: Log session data
error_log("Session in getUser.php: " . print_r($_SESSION, true));

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Database connection
    $host = '127.0.0.1';
    $db = 'student_portal';
    $user = 'root';
    $pass = '';

    try {
        // Create a new PDO instance
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch first_name and last_name from student_info table
        $stmt = $pdo->prepare("SELECT first_name, last_name FROM student_info WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode([
                "status" => "success",
                "user_id" => $user_id,
                "full_name" => $result['first_name'] . " " . $result['last_name']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "User not found"]);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        error_log("Database error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    }
} else {
    echo json_encode(["status" => "error", "user_id" => "Guest", "full_name" => "Unknown"]);
}
?>