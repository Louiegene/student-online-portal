<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
        <title>Student Portal - Limay Senior High School</title>
        <link rel="icon" href="/favicon.ico" sizes="16x16" type="image/x-icon">
        <link rel="apple-touch-icon" sizes="180x180" href="../../public/assets/images/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../public/assets/images/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../public/assets/images/favicon-16x16.png">
        <meta name="description" content="Welcome to Limay Senior High School, leading students to holistic success.">
        <meta name="keywords" content="Limay, Senior High School, Education, Enrollment, Programs">
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       
        <link rel="stylesheet" href="../../public/assets/css/styles.css">
        <link rel="stylesheet" href="../../public/assets/css/studentportalstyle.css">
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
    
                <!-- Navbar Links -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2">
                        <li class="nav-item"><a class="nav-link text-white" href="../../index.html" data-page="home">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="#" data-page="enrollment">Enrollment</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../../src/views/programs.html" data-page="programs">Programs</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../../src/views/announcement.html" data-page="announcements">Announcements</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../../src/views/about_us.html" data-page="about">About Us</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="../../src/views/contact_us.html" data-page="contact">Contact Us</a></li>
                        <li class="nav-item">
                            <a class="nav-link portal-button px-4 py-2 rounded-pill text-white mb-2" href="../../src/views/student_portal.php">
                                Student Portal
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="login-container">
            <div class="login-form">
                <h2>Student Portal Login</h2>
                <form id="loginForm" action="../../src/controllers/login.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="username" id="username" placeholder="Username" class="form-control" required>
                    </div>
                
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Enter Password" required>
                        <button type="button" class="toggle-password" data-target="password" aria-label="Show password">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                
                    <!-- Styled Error Message Box -->
                    <p id="errorMessage" class="error-message" style="display: none; color: red; font-weight: bold;"></p>
                
                    <button type="submit" class="login-button">Login</button>
                </form>
                
                <div class="forgot-password">
                    <span>Forgot Password?</span>
                    <a href="../../src/views/reset_password.php">Reset</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Success Popup -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark-check" fill="none" stroke-width="5" d="M14 27l7 7 16-16"/>
            </svg>
            <p>Login Successful!</p>
        </div>
    </div>

    <!-- Failed Popup -->
    <div id="failedPopup" class="popup">
        <div class="popup-content">
            <svg class="crossmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="crossmark-circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="crossmark-check" fill="none" stroke-width="5" d="M16 16l20 20M16 36l20-20"/>
            </svg>
            <p>Login Failed!</p>
        </div>
    </div>

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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/assets/js/login.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');

        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetId = button.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);

                if (passwordInput) {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                } else {
                    console.warn(`Password input with id "${targetId}" not found.`);
                }
            });
        });
    });
    </script>


</body>
</html>
