<?php
session_start();
require __DIR__ . '/../../config.php';

$user_id = $_SESSION['user_id'] ?? null;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    if ($user_id) {
        $stmt = $pdo->prepare("
            SELECT 
                Users.email,
                Users.role
            FROM 
                Users
            WHERE 
                Users.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $admin = [
                'email' => $user['email'],
                'role' => $user['role']
            ];
        } else {
            $admin = [];
        }
    } else {
        $admin = [];
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $admin = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Page - Limay Senior High School</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <!-- Include Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <!-- Include Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


    <link rel="stylesheet" href="../../public/assets/css/styles.css">
    <link rel="stylesheet" href="../../public/assets/css/studentprofilestyle.css">
    <link rel="stylesheet" href="../../public/assets/css/cropper.css">
    
</head>
    <body>
        <header class="header d-flex align-items-center py-2">
            <nav class="navbar navbar-expand-lg navbar-dark container-fluid px-3">
                <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">
                    <!-- School Logo and Name -->
                    <a class="navbar-brand d-flex align-items-center text-white me-auto" href="../../index.html" style="max-width: 50%;">
                        <img src="../../public/assets/images/limayshslogo.png" alt="School Logo" class="rounded-circle me-2" width="45" height="45">
                        <div class="d-flex flex-column">
                            <h1 class="h6 fw-bold m-0">LIMAY SENIOR HIGH SCHOOL</h1>
                            <p class="text-white m-0" style="font-size: 0.75rem; font-weight: normal;">LEADING STUDENTS TO HOLISTIC SUCCESS</p>
                        </div>
                    </a>
        
                    <!-- Navbar Toggler (for mobile view) -->
                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
        
                    <!-- Profile Dropdown -->
                    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav gap-2">
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/portal_dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/student_list.php">Students</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/student_grade_list.php">Grades</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/subject_list.php">Subjects</a></li>
                            
        
                            <!-- User Profile Dropdown -->
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white fw-bold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <!-- SVG as default -->
                                <svg width="30" height="30" viewBox="0 0 24 24" class="me-2 ms-2 profile-svg" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="12" fill="white"/> 
                                    <circle cx="12" cy="8" r="4" fill="#007bff"/>
                                    <path d="M6 20c0-4 3-6 6-6s6 2 6 6" fill="#007bff"/>
                                </svg>
                                
                                <!-- Image for dynamic profile picture -->
                                <img id="profileImage" src="" alt="Profile Picture" class="me-2 ms-2 profile-img" width="30" height="30" style="display: none; border-radius: 50%;" />
                                
                                <span>Administrator</span>
                            </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="../../src/views/admin_page.php">
                                        <i class="fas fa-user me-2"></i> Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#adminChangePasswordModal">
                                        <i class="fas fa-key me-2"></i> Change Password
                                    </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                    <li>
                                        <a class="dropdown-item" href="../../src/controllers/logout.php">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </li>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
    
<main class="main-content text-center py-5">     
    <!-- Admin Details Container -->
    <div class="profile-container mx-auto p-4 shadow rounded text-start">
        <form id="profileForm" enctype="multipart/form-data" method="POST">
            <div class="d-flex flex-column align-items-center">
                <img src="../../public/assets/images/user_profile.png" alt="Profile Picture" id="previewImage" class="profile-picture">
                <input type="file" id="profileInput" name="profile_picture" accept="image/*" style="display: none;">
                <button type="button" class="change-pic-button" onclick="openCropperModal()">Change Picture</button>
            </div>
            
            <!-- Modal for cropping -->
            <div id="cropperModal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                    <h3>Crop Profile Picture</h3>
                    <button type="button" class="close-button" onclick="closeCropperModal()">×</button>
                    </div>
                    <div class="modal-body">
                    <div class="img-container">
                        <img id="imageToCrop" src="" alt="Image to Crop">
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="cancel-button" onclick="closeCropperModal()">Cancel</button>
                    <button type="button" class="crop-button" onclick="cropImage()">Save</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="profile-info">
            <h1 id="adminFullName" class="fw-bold text-primary">Administrator</h1>
            <p class="mb-1"><strong>Email:</strong> 
                <span id="email"><?php echo htmlspecialchars($admin['email'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>Role:</strong> 
                <span id="role"><?php echo htmlspecialchars($admin['role'] ?? 'N/A'); ?></span>
            </p>
        </div>
    </div>
</main>    
        
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

    <!-- Change Password Modal -->
<div class="modal fade" id="adminChangePasswordModal" tabindex="-1" aria-labelledby="adminChangePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="adminChangePasswordForm" action="../../src/controllers/admin_change_password.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adminChangePasswordModalLabel">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="mb-3">
              <label for="currentPassword" class="form-label">Current Password</label>
              <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
          </div>
          <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input type="password" class="form-control" id="newPassword" name="newPassword" required>
          </div>
          <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
          </div>
      </div>
      <div id="passwordMismatchError" class="text-danger fw-bold mb-2" style="display: none;">
            Passwords do not match.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Change Password</button>
      </div>
    </form>
  </div>
</div>


<script src="../../public/assets/js/admin_change_password.js"></script>
<script src="../../public/assets/js/get_profile_picture.js"></script>
<script src="../../public/assets/js/change_profile_picture.js"></script>
<script src="https://unpkg.com/browser-image-compression@latest/dist/browser-image-compression.js"></script>

    </body>
</html>