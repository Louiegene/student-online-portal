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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> -->
    
    <script src="https://kit.fontawesome.com/194398f2ad.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

    
    <link rel="stylesheet" href="../../public/assets/css/styles.css">
    <link rel="stylesheet" href="../../public/assets/css/student_records_style.css">
    <!-- <link rel="stylesheet" href="../../public/assets/css/gradestyle.css"> -->
    <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
    
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
     
    <!-- Main Content -->
    <main class="main-content py-5">
        <div class="container">
            <div class="student-records-container">
                <h2 class="text-center mb-4"><strong>Academic Records</strong></h2>
                
                <!-- Student Information Section -->
                <div class="student-info mb-4 p-3 border rounded bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Student Name: </strong><span id="student-first-name"></span> <span id="student-last-name"></span></p>
                            <p class="mb-1"><strong>Learner Reference Number: </strong><span id="student-lrn"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Track: </strong><span id="student-track"></span></p>
                            <p class="mb-1"><strong>Strand: </strong><span id="student-strand"></span></p>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4" id="recordTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="grade11-tab" data-bs-toggle="tab" data-bs-target="#grade11" type="button" role="tab" aria-controls="grade11" aria-selected="true">Grade 11</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="grade12-tab" data-bs-toggle="tab" data-bs-target="#grade12" type="button" role="tab" aria-controls="grade12" aria-selected="false">Grade 12</button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="recordTabContent">
                    <!-- Grade 11 Tab -->
                    <div class="tab-pane fade show active" id="grade11" role="tabpanel" aria-labelledby="grade11-tab">
                        <div class="student-records-container text-center mb-4">
                            <h3 class="mb-2"><strong>Grade 11 Academic Records</strong></h3>
                            <p>
                                <strong>School Year: </strong>
                                <span id="school-year">-</span>
                            </p>
                        </div>
                            
                        <!-- First Semester -->
                        <div class="semester-section mb-5">
                            <h4 class="student-info mb-3 text-center"><strong>First Semester</strong></h4>
                            
                            <!-- First Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">First Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="academic-records-table table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade11-sem1-q1-grades">
                                                <!-- Grades would be populated dynamically here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Second Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Second Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade11-sem1-q2-grades">
                                                <!-- Grades would be populated dynamically here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Second Semester -->
                        <div class="semester-section">
                            <h4 class="student-info mb-3 text-center"><strong>Second Semester</strong></h4>
                            
                            <!-- Third Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Third Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade11-sem2-q3-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Fourth Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Fourth Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade11-sem2-q4-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grade 12 Tab -->
                    <div class="tab-pane fade" id="grade12" role="tabpanel" aria-labelledby="grade12-tab">
                        <div class="student-records-container text-center mb-4">
                            <h3 class="mb-2"><strong>Grade 12 Academic Records</strong></h3>
                            <p>
                                <strong>School Year: </strong>
                                <span id="school-year">-</span>
                            </p>
                        </div>
                            
                        <!-- First Semester -->
                        <div class="semester-section mb-5">
                            <h4 class="student-info mb-3 text-center"><strong>First Semester</strong></h4>
                            
                            <!-- First Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">First Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade12-sem1-q1-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Second Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Second Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade12-sem1-q2-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Second Semester -->
                        <div class="semester-section">
                            <h4 class="student-info mb-3 text-center"><strong>Second Semester</strong></h4>
                            
                            <!-- Third Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Third Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade12-sem2-q3-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Fourth Quarter -->
                            <div class="quarter-section mb-4">
                                <h5 class="mb-2">Fourth Quarter</h5>
                                <div class="table-responsive">
                                    <table class="grade-table text-center align-middle">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="grade12-sem2-q4-grades">
                                            <!-- Will be populated dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

    
    <script src="../../public/assets/js/username.js"></script>
    <script src="../../public/assets/js/get_profile_picture.js"></script>
    <!-- <script src="../../public/assets/js/get_user_id.js"></script> -->
    <script src="../../public/assets/js/student_records.js"></script>
    <script src="../../public/assets/js/get_user_id.js"></script>
    
    
   
    </body>
</html>