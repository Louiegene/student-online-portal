<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Reset Password - Limay Senior High School</title>
        <link rel="icon" href="/favicon.ico" sizes="16x16" type="image/x-icon">
        <link rel="apple-touch-icon" sizes="180x180" href="../../public/assets/images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../public/assets/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../public/assets/images/favicon-16x16.png">
        <meta name="description" content="Welcome to Limay Senior High School, leading students to holistic success.">
        <meta name="keywords" content="Limay, Senior High School, Education, Enrollment, Programs">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
        <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <link rel="stylesheet" href="../../public/assets/css/styles.css">
        <link rel="stylesheet" href="../../public/assets/css/resetpasswordstyle.css">
    </head>
<body>
    
    <header class="header d-flex align-items-center py-2">
        <nav class="navbar navbar-expand-lg navbar-dark container-fluid px-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">
                <!-- School Logo and Name (Left-aligned with limited width) -->
                <a class="navbar-brand d-flex align-items-center text-white me-auto" href="../../index.html" style="max-width: 50%;">
                    <img src="../../public/assets/images/limayshslogo.png" alt="School Logo" class="rounded-circle me-2" width="45" height="45">
                    <div class="d-flex flex-column">
                        <h1 class="h6 fw-bold m-0">LIMAY SENIOR HIGH SCHOOL</h1>
                        <p class="text-white m-0" style="font-size: 0.75rem; font-weight: normal;">LEADING STUDENTS TO HOLISTIC SUCCESS</p>
                    </div>
                </a>
    
                <!-- Navbar Toggler (Right-aligned) -->
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
        </nav>
 </header>


 <main>
    <div class="login-container">
        <div class="login-form">
            <h2>Reset Password</h2>
            <form id="resetForm" action="../../src/controllers/reset_email_template.php" method="POST">
                <div class="input-group">
                    <label for="userEmail" class="visually-hidden">Email</label>
                    <input type="email" name="userEmail" id="userEmail" placeholder="Email" required>
                </div>

                <!-- Google reCAPTCHA -->
                <div class="g-recaptcha" data-sitekey="6Lel2wArAAAAADll8crUXR9-nmLXdPZAL6skB_0o"></div>

                <!-- Error Message -->
                <p id="errorMessage" class="error-message" style="display: none;"></p>

                <div class="input-group">
                    <button type="submit" class="login-button">Reset</button>
                </div>

                <div class="input-group">
                    <button type="button" class="back-button" onclick="history.back()">Back</button>
                </div>
            </form>
        </div>
    </div>
</main>

<!-- Link to external CSS file -->
<link rel="stylesheet" href="path/to/your/styles.css">


    <footer class="footer d-flex flex-column flex-md-row justify-content-between align-items-center p-4 flex-wrap">
        <div class="d-flex align-items-center text-start">
            <div class="footer-logo-container">
                <img src="../../public/assets/images/limayshslogo.png" alt="School Logo" width="50">
            </div>
            <div class="ms-3">
                <h1 class="h5 text-white fw-bold m-0 text-start">LIMAY SENIOR HIGH SCHOOL</h1>
                <p class="text-white m-0 text-start">© 2025 Limay Senior High School. All Rights Reserved.</p>
            </div>
        </div>
    
        <div class="d-flex mt-3 mt-md-0 ms-md-auto footer-links">
            <a href="../../src/views/privacypolicy.html" class="text-white mx-2 text-decoration-none">Privacy Policy</a>
            <a href="#" class="text-white mx-2 text-decoration-none">Citizen's Charter</a>
            <a class="text-white mx-2" href="https://www.facebook.com/LimaySeniorHighSchool" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i>
            </a>
        </div>
    </footer>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="../../public/assets/js/togglePassword.js"></script>
    <script src="../../public/assets/js/recaptcha.js"></script>
</body>
</html>
