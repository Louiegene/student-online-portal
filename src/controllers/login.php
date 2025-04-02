<?php
session_start();
require 'config.php';

header('Content-Type: application/json'); // Ensure response is JSON

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

try {
    $stmt = $pdo->prepare("SELECT user_id, username, password, first_name, last_name FROM Users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (hash('sha256', $password) === $row['password']) {
            // Store user details in session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];

            // Debugging: Check session values
            error_log("Session Data: " . print_r($_SESSION, true));

            echo json_encode(["status" => "success"]);
            exit;
        }
    }

    // Either username not found or incorrect password
    echo json_encode(["status" => "error", "message" => "❌ Invalid username or password."]);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Database error."]);
}
exit;
?>
