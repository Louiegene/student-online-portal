<?php
ob_clean();
header('Content-Type: application/json');
session_start();
require __DIR__ . '/../../config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($pdo)) {
    error_log("Database connection not initialized.");
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Internal server error."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = trim($_POST['currentPassword'] ?? '');
    $newPassword = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');

    // Empty field validation
    if (!$currentPassword || !$newPassword || !$confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // Password confirmation
    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // Password strength
    if (strlen($newPassword) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'New password must be at least 8 characters.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT password FROM Users WHERE user_id = :userId");
        $stmt->execute(['userId' => $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            exit;
        }

        if (!password_verify($currentPassword, $row['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
            exit;
        }

        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);

        $updateStmt = $pdo->prepare("UPDATE Users SET password = :newPassword WHERE user_id = :userId");
        if ($updateStmt->execute([
            'newPassword' => $hashedNewPassword,
            'userId' => $user_id
        ])) {
            session_unset();
            session_destroy();
            echo json_encode(['status' => 'success', 'message' => 'Password changed successfully. Please log in again.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
        }
    } catch (PDOException $e) {
        error_log("DB error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'A database error occurred.']);
    }
}
?>
