<?php
session_start();
require __DIR__ . '/../../config.php'; // Include the config file

// Assuming you have stored user_id in the session when the user logged in
$user_id = $_SESSION['user_id'] ?? null; // Get user_id from session

try {
    // Database connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulated prepared statements
    ]);

    // Check if user_id is set
    if ($user_id) {
        // Query to fetch student profile data using user_id
        $stmt = $pdo->prepare("
            SELECT 
                si.first_name,
                si.last_name,
                u.email,
                si.lrn,
                si.gender,
                si.grade_level,
                si.section,
                t.trackname,
                s.strandname,
                si.specific_strand,
                si.enrollment_status
            FROM 
                student_info si
            JOIN 
                Users u ON si.user_id = u.user_id
            JOIN 
                Track t ON si.track_id = t.track_id
            JOIN 
                Strand s ON si.strand_id = s.strand_id
            WHERE 
                si.user_id = :user_id
        ");
        $stmt->execute(['user_id' => $user_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no data is found, initialize an empty array
        if (!$student) {
            $student = [];
        }
    } else {
        echo "<p>You are not logged in. Please log in to view your profile.</p>";
    }
} catch (PDOException $e) {
    // Log the error (optional) and show a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    echo "<p>Unable to fetch profile data at this time. Please try again later.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Student Page - Limay Senior High School</title>
    <link rel="icon" href="/favicon.ico" sizes="16x16" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../../public/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../public/assets/images/favicon-16x16.png">
    <meta name="description" content="Welcome to Limay Senior High School, leading students to holistic success.">
    <meta name="keywords" content="Limay, Senior High School, Education, Enrollment, Programs">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    
    <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Include Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <!-- Include Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    
    <link rel="stylesheet" href="../../public/assets/css/styles.css">
    <link rel="stylesheet" href="../../public/assets/css/studentprofilestyle.css">
    <link rel="stylesheet" href="../../public/assets/css/tablestyle.css">
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
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/view_grades.php">Grades</a></li>
                            <li class="nav-item"><a class="nav-link text-white" href="../../src/views/student_academic_records.php">Academic Records</a></li>
        
                            <!-- User Profile Dropdown -->
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
                                        <a class="dropdown-item" href="../../src/views/student_page.php">
                                        <i class="fas fa-user me-2"></i> Profile
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
                                        <a class="dropdown-item" href="../../src/controllers/logout.php">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
     
        

<main class="main-content text-center py-5">     
    <!-- Student Profile Container -->
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
            <h1 id="studentFullName" class="fw-bold text-color">
            <?php echo htmlspecialchars(($student['first_name'] ?? 'N/A') . ' ' . ($student['last_name'] ?? 'N/A')); ?>
            </h1>
            <p class="mb-1"><strong>Email:</strong> 
                <span id="email"><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>LRN:</strong> 
                <span id="lrn"><?php echo htmlspecialchars($student['lrn'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>Gender:</strong> 
                <span id="gender"><?php echo htmlspecialchars($student['gender'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>Grade Level:</strong> 
                <span id="gradeLevel"><?php echo htmlspecialchars($student['grade_level'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>Section:</strong> 
                <span id="sectionName"><?php echo htmlspecialchars($student['section'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-1"><strong>Track:</strong> 
                <span id="track"><?php echo htmlspecialchars($student['trackname'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-0"><strong>Strand:</strong> 
                <span id="strand"><?php echo htmlspecialchars($student['strandname'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-0"><strong>Specific Strand:</strong> 
                <span id="strand"><?php echo htmlspecialchars($student['specific_strand'] ?? 'N/A'); ?></span>
            </p>
            <p class="mb-0"><strong>Enrollment Status:</strong> 
                <span id="status"><?php echo htmlspecialchars($student['enrollment_status'] ?? 'N/A'); ?></span>
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
        
        <script src="../../public/assets/js/username.js"></script>
        <script src="../../public/assets/js/get_profile_picture.js"></script>
        <script src="../../public/assets/js/change_profile_picture.js"></script>
    </body>
</html>