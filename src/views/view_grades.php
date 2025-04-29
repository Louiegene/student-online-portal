<?php
// Start session and include the backend handler
session_start();
require dirname(dirname(__DIR__)) . '/config.php';
include __DIR__ . '../../controllers/grades_controller.php';

// Get the user_id from session
$user_id = $_SESSION['user_id'] ?? null;

// Check if user is logged in
if (!$user_id) {
    // Redirect to login page if not logged in
    header("Location: ../../src/views/student_portal.php");
    exit;
}

// Get filters from GET request or set defaults
$selectedQuarter = isset($_GET['quarter']) ? $_GET['quarter'] : '1st';
$selectedGradeLevel = isset($_GET['grade_level']) ? $_GET['grade_level'] : '11';
$selectedSemester = isset($_GET['semester']) ? $_GET['semester'] : '1st';

// Validate selected values
$validQuarters = ['1st', '2nd', '3rd', '4th'];
$validGradeLevels = ['11', '12'];
$validSemesters = ['1st', '2nd'];

if (!in_array($selectedQuarter, $validQuarters)) {
    $selectedQuarter = '1st';
}

if (!in_array($selectedGradeLevel, $validGradeLevels)) {
    $selectedGradeLevel = 'Grade 11';
}

if (!in_array($selectedSemester, $validSemesters)) {
    $selectedSemester = '1st';
}

// Fetch grades from the controller
$gradesController = new GradesController($host, $port, $dbname, $username, $password);
$grades = $gradesController->getStudentGrades($user_id, $selectedQuarter, $selectedGradeLevel, $selectedSemester);

// Get display name (if available)
$displayName = $_SESSION['display_name'] ?? 'Student';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Student Grades - Limay Senior High School</title>
    <link rel="icon" href="/favicon.ico" sizes="16x16" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="../../public/assets/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../public/assets/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../public/assets/images/favicon-16x16.png">
    <meta name="description" content="Welcome to Limay Senior High School, leading students to holistic success.">
    <meta name="keywords" content="Limay, Senior High School, Education, Enrollment, Programs">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    
    <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
    
    <link rel="stylesheet" href="../../public/assets/css/styles.css">
    <link rel="stylesheet" href="../../public/assets/css/studentprofilestyle.css">
    <link rel="stylesheet" href="../../public/assets/css/gradesstyle.css">
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
    
                <!-- Navigation Links and Profile Dropdown -->
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav gap-2">
                        <li class="nav-item"><a class="nav-link text-white" href="view_grades.php">Grades</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="student_academic_records.php">Academic Records</a></li>
    
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
                                    <?php echo htmlspecialchars($displayName); ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="student_page.php">
                                        <i class="fas fa-user me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="change_password.php">
                                        <i class="fas fa-key me-2"></i> Change Password
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="../controllers/logout.php">
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

    <!-- Main Content -->
    <main class="main-content text-center py-5">
        <div>
            <!-- Filter Section -->
            <div class="filter-container">
                <form method="GET" action="view_grades.php" class="d-flex flex-wrap justify-content-center gap-3" id="gradesFilterForm">
                    <!-- Grade Level Filter -->
                    <div class="filter-group">
                        <label for="gradeLevel" class="filter-label">Grade Level</label>
                        <select name="grade_level" id="gradeLevel" class="form-select">
                            <?php foreach ($validGradeLevels as $gradeLevel): ?>
                                <option value="<?= htmlspecialchars($gradeLevel) ?>" <?= ($selectedGradeLevel === $gradeLevel) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($gradeLevel) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Semester Filter -->
                    <div class="filter-group">
                        <label for="semesterFilter" class="filter-label">Semester</label>
                        <select name="semester" id="semesterFilter" class="form-select">
                            <?php foreach ($validSemesters as $semester): ?>
                                <option value="<?= htmlspecialchars($semester) ?>" <?= ($selectedSemester === $semester) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($semester) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Quarter Filter -->
                    <div class="filter-group">
                        <label for="quarterFilter" class="filter-label">Quarter</label>
                        <select name="quarter" id="quarterFilter" class="form-select">
                            <?php foreach ($validQuarters as $quarter): ?>
                                <option value="<?= htmlspecialchars($quarter) ?>" <?= ($selectedQuarter === $quarter) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($quarter) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    
                </form>
            </div>

            <!-- Table to Display Grades -->
            <div>
                <table id="gradesTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($grades)): ?>
                            <?php foreach ($grades as $grade): ?>
                                <tr>
                                    <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                                    <td><?= htmlspecialchars($grade['grade']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="no-grades">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No grades available for the selected filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            <a href="privacypolicy.html" class="text-white mx-2 text-decoration-none">Privacy Policy</a>
            <a href="#" class="text-white mx-2 text-decoration-none">Citizen's Charter</a>
            <a class="text-white mx-2" href="https://www.facebook.com/LimaySeniorHighSchool" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook"></i>
            </a>
        </div>
    </footer>
    
    <script src="../../public/assets/js/username.js"></script>
    <script src="../../public/assets/js/get_profile_picture.js"></script>
    <script src="../../public/assets/js/view_grades.js"></script>
</body>
</html>