<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = $_POST["userEmail"];
    $recaptchaResponse = $_POST["g-recaptcha-response"];

    // Verify reCAPTCHA
    $secretKey = "6Lel2wArAAAAAP6PgbqztcgHV1uy1yc3i2X3Fv-d";
    $verifyURL = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}";
    
    $response = file_get_contents($verifyURL);
    $responseKeys = json_decode($response, true);

    if (!$responseKeys["success"]) {
        die("reCAPTCHA verification failed. Please try again.");
    }

    // Process password reset (send email, update DB, etc.)
    echo "Password reset request received. Check your email.";
}
?>
