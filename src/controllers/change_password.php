<?php
require '/src/config/database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT);

    // Validate token
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Invalid or expired token.");
    }

    // Update user password
    $stmt = $pdo->prepare("UPDATE Users SET password = ? WHERE email = ?");
    $stmt->execute([$new_password, $user["email"]]);

    // Delete the used token
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);

    echo "Password updated successfully. You can now log in.";
}
?>
