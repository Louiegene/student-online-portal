<?php
require __DIR__ . '/../../config.php'; // Include the config.php file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

    try {
        // Validate token
        $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if (!$user) {
            // Token is invalid or expired
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
            exit;
        }

        // Update user password
        $stmt = $pdo->prepare("UPDATE Users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $user["email"]]);

        // Delete the used token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
        $stmt->execute([$token]);

        // Password updated successfully
        echo json_encode(['status' => 'success', 'message' => 'Password updated successfully. You can now log in.']);
        exit;

    } catch (Exception $e) {
        // Handle any errors
        echo json_encode(['status' => 'error', 'message' => 'An error occurred while updating the password.']);
        exit;
    }
}
?>
