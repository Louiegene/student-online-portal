<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Limay SHS</title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <link rel="stylesheet" href="/public/css/studentportalstyle.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header class="container-fluid d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center">
            <img alt="School logo" class="rounded-circle" height="65" src="/public/images/limayshslogo.png" width="65"/>
            <div class="ms-3">
                <h1 class="h5 text-white fw-bold m-0">LIMAY SENIOR HIGH SCHOOL</h1> 
                <p class="small text-white m-0">LEADING STUDENTS TO HOLISTIC SUCCESS</p>
            </div>
        </div>
    </header>

    <main class="login-container">
        <div class="login-form reset-form">
            <h2>Reset Password</h2>

            <?php if (isset($_SESSION["message"])): ?>
                <p class="alert alert-danger"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
            <?php endif; ?>

            <form action="../controllers/process_reset.php" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6Lel2wArAAAAADll8crUXR9-nmLXdPZAL6skB_0o"></div>

                <div class="button-group">
                    <button type="submit" class="reset-button">Reset Password</button>
                    <a href="../student_portal.html" class="cancel-button">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
        <p class="small text-white m-0">© 2025 Limay Senior High School. All Rights Reserved.</p>
    </footer>
</body>
</html>
