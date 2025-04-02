<?php
require '../src/config/database.php';

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $recaptcha_response = $_POST["g-recaptcha-response"];

    // Verify Google reCAPTCHA
    $recaptcha_secret = "6Lel2wArAAAAAP6PgbqztcgHV1uy1yc3i2X3Fv-d";
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        "secret" => $recaptcha_secret,
        "response" => $recaptcha_response
    ];
    $options = [
        "http" => [
            "header" => "Content-Type: application/x-www-form-urlencoded",
            "method" => "POST",
            "content" => http_build_query($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = json_decode(file_get_contents($url, false, $context));

    if (!$result->success) {
        echo json_encode(["status" => "error", "message" => "reCAPTCHA verification failed. Please try again."]);
        exit;
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT email FROM Users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(["status" => "error", "message" => "Email not found."]);
        exit;
    }

    // Generate a unique token
    $token = bin2hex(random_bytes(32));
    $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Store the token in the database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    if (!$stmt->execute([$email, $token, $expires_at])) {
        echo json_encode(["status" => "error", "message" => "Failed to store reset token."]);
        exit;
    }

    // Send the reset email
    $reset_link = "http://yourwebsite.com/views/new_password.php?token=$token";
    $subject = "Password Reset Request";
    $message = "Click the following link to reset your password: $reset_link";
    $headers = "From: no-reply@yourwebsite.com\r\n";

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(["status" => "success", "message" => "Check your email for the password reset link."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send reset email."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>