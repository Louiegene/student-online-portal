<?php
session_start();
require __DIR__ . '/../../config.php'; // Include the config.php file

try {
    // Database connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulated prepared statements
    ]);

    // Base query to fetch student list
    $sql = "SELECT * FROM student_info";
    $stmt = $pdo->prepare($sql); // Prepare the query
    $stmt->execute(); // Execute the query

    // Fetch the results
    $students = $stmt->fetchAll(); // Get all the results

    // Dashboard

    // Total Enrolled Students
    $totalQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled'");
    $total = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Grade 11
    $g11Query = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND grade_level = 11");
    $grade11 = $g11Query->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Grade 12
    $g12Query = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND grade_level = 12");
    $grade12 = $g12Query->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Male
    $maleQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND gender = 'Male'");
    $male = $maleQuery->fetch(PDO::FETCH_ASSOC)['total'];

    // Total Female
    $femaleQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND gender = 'Female'");
    $female = $femaleQuery->fetch(PDO::FETCH_ASSOC)['total'];

} catch (PDOException $e) {
    // Log the error (optional) and show a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    echo "<p>Unable to fetch student data at this time. Please try again later.</p>";
    exit; // Stop further execution if there's a database error
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard - Limay Senior High School</title>
    <link rel="icon" href="/favicon.ico" sizes="16x16" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../../public/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../public/assets/images/favicon-16x16.png">
    <meta name="description" content="Welcome to Limay Senior High School, leading students to holistic success.">
    <meta name="keywords" content="Limay, Senior High School, Education, Enrollment, Programs">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    
    <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS (requires Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <link rel="stylesheet" href="../../public/assets/css/styles.css">
    <link rel="stylesheet" href="../../public/assets/css/portal_dashboard.css">

    
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
            <h1 style="margin: 0; text-align: center; font-size: 24px;"><strong>Administrator Dashboard</strong></h1>
            
            <div class="dashboard">
                    <!-- Total Enrolled Students Card -->
                    <div class="card" style="background-color: #004080; color: white;">
                        <div class="card-content">
                            <div class="card-info">
                                <h1><strong><?php echo $total; ?></strong></h1>
                            </div>
                            <div class="card-info">
                                <i class="fa fa-users card-icon-left"></i> <!-- Left Icon -->
                            </div>
                            <!-- Card title-->
                            <div class="card-title">
                                <p><strong>Total Enrolled Students</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Grade 11 Card -->
                    <div class="card" style="background-color: #004080; color: white;">
                        <div class="card-content">
                            <div class="card-info">
                                <h1><strong><?php echo $grade11; ?></strong></h1>
                            </div>
                            <div class="card-info">
                                <i class="fa fa-users card-icon-left"></i> <!-- Left Icon -->
                            </div>
                            <div class="card-title">
                                <p><strong>Grade 11</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Grade 12 Card -->
                    <div class="card" style="background-color: #004080; color: white;">
                        <div class="card-content">
                            <div class="card-info">
                                <h1><strong><?php echo $grade12; ?></strong></h1>
                            </div>
                            <div class="card-info">
                                <i class="fa fa-users card-icon-left"></i> <!-- Left Icon -->
                            </div>
                            <div class="card-title">
                                <p><strong>Grade 12</strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Male / Female Card -->
                    <div class="card" style="background-color: #004080; color: white;">
                        <div class="card-content">
                            <div class="card-info">
                                <h2><strong><?php echo $male . " / " . $female; ?></strong></h2>
                            </div>
                            <div class="card-icons">
                                <i class="fa fa-male card-icon-left"></i> <!-- Left Icon -->
                                <i class="fa fa-female card-icon-right"></i> <!-- Right Icon -->
                            </div>
                            <div class="card-title">
                                <p><strong>Male / Female</strong></p>
                            </div>
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
    <script src="../../public/assets/js/get_profile_picture.js"></script>
      
    </body>
</html>