<?php
session_start();
ob_clean();
require __DIR__ . '/../../config.php';

ini_set('display_errors', 0);
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
    echo json_encode(["status" => "error", "message" => "Database connection error."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = trim($_POST['currentPassword']);
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Empty fields check
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    if (strlen($newPassword) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters.']);
        exit;
    }

    try {
        // Fetch hashed password from DB
        $query = "SELECT password FROM Users WHERE user_id = :userId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'User not found.']);
            exit;
        }

        // Use password_verify to check current password
        if (!password_verify($currentPassword, $row['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect.']);
            exit;
        }

        // Hash the new password with bcrypt
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

        // Update password in database
        $updateQuery = "UPDATE Users SET password = :newPassword WHERE user_id = :userId";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newPassword', $hashedNewPassword, PDO::PARAM_STR);
        $updateStmt->bindParam(':userId', $user_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update password.']);
            exit;
        }

    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
        exit;
    }
}
?>



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
        <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
        
        <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        
        <link rel="stylesheet" href="../../public/assets/css/styles.css">
        <link rel="stylesheet" href="../../public/assets/css/changepasswordstyle.css">
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
    
               <!-- Profile Dropdown aligned to the right -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../../src/views/view_grades.php">Grades</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../src/views/student_academic_records.php">Academic Records</a></li>
        
                    <!-- Dropdown Menu -->
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- SVG fallback avatar -->
                         <svg id="defaultAvatar" width="30" height="30" viewBox="0 0 24 24" class="me-2 ms-2 profile-svg" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="12" fill="white"/> 
                            <circle cx="12" cy="8" r="4" fill="#007bff"/>
                            <path d="M6 20c0-4 3-6 6-6s6 2 6 6" fill="#007bff"/>
                        </svg>

                    <!-- Dynamic image profile -->
                        <img id="profileImage" src="" alt="Profile Picture" class="me-2 ms-2 profile-img" width="30" height="30" style="display: none; border-radius: 50%;" />

                                <span id="loggedInUserDropdown">
                                    <?php echo isset($displayName) ? htmlspecialchars($displayName) : 'Unknown Name'; ?>
                                </span>
                            </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <!-- Dropdown Menu with Full Name -->
                                <a class="dropdown-item" href="../../src/views/student_page.php">
                                    <i class="fas fa-user me-2"></i> <span>Profile</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="../../src/views/change_password.php">
                                    <i class="fas fa-key me-2"></i> Change Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="../../src/controllers/logout.php" method="POST">
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>                        
            </div>
        </nav>
 </header>

 <main>
    <div class="container">
        <div class="login-container">
            <div class="login-form">
                <h2>Change Password</h2>
                <form id="changePasswordForm" method="POST" action="../../src/views/change_password.php">

                    
                    <!-- Current Password -->
                    <div class="input-group">
                        <input type="password" name="currentPassword" id="currentPassword" placeholder="Enter Current Password" required>
                        <button type="button" class="toggle-password" data-target="currentPassword" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- New Password -->
                    <div class="input-group">
                        <input type="password" name="newPassword" id="newPassword" placeholder="Enter New Password" required>
                        <button type="button" class="toggle-password" data-target="newPassword" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Confirm Password -->
                    <div class="input-group">
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm New Password" required>
                        <button type="button" class="toggle-password" data-target="confirmPassword" aria-label="Toggle password visibility">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Password Strength Guide -->
                    <div id="password-strength-guide">
                        <p><strong>Password must contain:</strong></p>
                        <ul>
                            <li id="lengthCheck">❌ At least 8 characters</li>
                            <li id="uppercaseCheck">❌ At least 1 uppercase letter</li>
                            <li id="lowercaseCheck">❌ At least 1 lowercase letter</li>
                            <li id="numberCheck">❌ At least 1 number</li>
                            <li id="specialCheck">❌ At least 1 special character (@$!%*?&)</li>
                        </ul>
                    </div>

                    <!-- Error Message -->
                    <p id="errorMessage" class="error-message" style="display: none; color: red; font-weight: bold;">
                        ❌ Passwords do not match!
                    </p>

                    <!-- Submit Button -->
                    <button type="submit" class="login-button">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</main>


    <footer class="d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
        <div class="d-flex align-items-center text-start">
            <div class="footer-logo-container">
                <img src="../../public/assets/images/limayshslogo.png" alt="School Logo" width="65">
            </div>                
            <div class="ms-3">
                <h1 class="h5 text-white fw-bold m-0 text-start">LIMAY SENIOR HIGH SCHOOL</h1>
                <p class="small text-white m-0 text-start">© 2025 Limay Senior High School. All Rights Reserved.</p>
            </div>
        </div>
        
        <div class="d-flex mt-3 mt-md-0 ms-md-auto footer">
            <a href="../../src/views/privacypolicy.html" class="text-white mx-2">Privacy Policy</a>
            <a href="#" class="text-white mx-2">Citizen's Charter</a>
            <a class="text-white mx-2" href="https://www.facebook.com/LimaySeniorHighSchool" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i>
            </a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../public/assets/js/get_profile_picture.js"></script>
    <script src="../../public/assets/js/togglePassword.js"></script>
    <script src="../../public/assets/js/passwordvalidation.js"></script>
    <script src="../../public/assets/js/username.js"></script>
    
    <script>
    // Attach event listener for the form submission
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('changePasswordForm').addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent the form from submitting the traditional way

        var form = this;
        var formData = new FormData(form);

        // Basic client-side validation for matching passwords
        var newPassword = document.getElementById('newPassword').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                icon: 'error',
                html: '<b>Passwords do not match!</b>',
                width: '400px',
                showConfirmButton: true,
                allowOutsideClick: false
            });
            return; // Stop the form submission if passwords don't match
        }

        // Send the form data to PHP using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        // Error handler for the AJAX request
        xhr.onerror = function () {
            Swal.fire({
                title: '<strong class="text-danger">ERROR</strong>',
                icon: 'error',
                html: '<b>An error occurred while submitting the form. Please try again later.</b>',
                width: '400px',
                showConfirmButton: true,
                allowOutsideClick: false
            });
        };

        // Handle response after form submission
        xhr.onload = function () {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);  // Parse the JSON response

                if (data.status === 'success') {
                    Swal.fire({
                        title: '<strong class="text-success">SUCCESS</strong>',
                        icon: 'success',
                        html: '<b>Password Changed Successfully!</b>',
                        width: '400px',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = "../../src/views/student_portal.php";  // Redirect to dashboard after success
                    });
                } else {
                    Swal.fire({
                        title: '<strong class="text-danger">ERROR</strong>',
                        icon: 'error',
                        html: `<b>${data.message}</b>`,  // Show the error message from the PHP response
                        width: '400px',
                        showConfirmButton: true,
                        allowOutsideClick: false
                    });
                }
            } else {
                Swal.fire({
                    title: '<strong class="text-danger">ERROR</strong>',
                    icon: 'error',
                    html: `<b>An unexpected error occurred. Status code: ${xhr.status}</b>`,
                    width: '400px',
                    showConfirmButton: true,
                    allowOutsideClick: false
                });
            }
        };

        xhr.send(formData);  // Send the form data
    });
});
</script>
</body>
</html>
