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
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../../public/assets/css/bootstrap/bootstrap.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../../public/assets/css/styles.css">

    <!-- Bootstrap Bundle with Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header class="header d-flex align-items-center py-2">
        <nav class="navbar navbar-expand-lg navbar-dark container-fluid px-3">
            <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">
                <!-- School Logo and Name -->
                <a class="navbar-brand d-flex align-items-center text-white me-auto" href="#" style="max-width: 50%;">
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
    <div style="width: 90%; max-width: 1200px; margin: 0 auto; margin-bottom: 10px;">
        <h1 style="margin: 0; text-align: center; font-size: 24px;"><strong>Subject List</strong></h1>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; flex-wrap: wrap;">
            <form method="get" action="" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <label for="lrn" style="font-size: 16px; font-weight: bold;">Search</label>
                <input 
                    type="text" 
                    id="searchInput" 
                    name="lrn" 
                    value="<?= htmlspecialchars($search_subject) ?>" 
                    style="padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; width: 100%; max-width: 300px;"
                    placeholder="Search by Code or Name"
                >
            </form>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Add Subject</button>
        </div>
    </div>

    <!-- Table to Display Subject List -->
    <div style="width: 90%; max-width: 1200px; margin: 0 auto; height: 600px; overflow-y: auto;">
        <table id="studentTable" class="table table-striped" style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">Subject Code</th>
                    <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">Subject Name</th>
                    <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">Subject Type</th>
                    <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6; text-align: center; vertical-align: middle;">Actions</th>
                </tr>
            </thead>
            <tbody id="subjectTableBody">
                <!-- Data will be injected here by JavaScript -->
            </tbody>
        </table>
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
    </footer>

    <!-- Popup Modal for Add Subject -->
    <div class="modal fade btn-primary" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                    <button type="button" id="addSubjectBtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSubjectForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="subject_code" class="form-label">Subject Code</label>
                            <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject Name</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                        </div>
                        <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Type</label>
                        <select class="form-select" id="subject_type" name="subject_type" required>
                                <option value="" disabled selected>Select Subject Type</option>
                                <option value="Core Subject">Core Subject</option>
                                <option value="Applied Subject">Applied Subject</option>
                                <option value="Specialized Subject">Specialized Subject</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End of Add Subject Modal-->

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
<!--End of Change Password Modal-->

<!-- Modal Edit Subject -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for editing subject -->
                <form id="editSubjectForm">
                    <input type="hidden" id="edit-subject-id" name="subject_id">
                    <div class="mb-3">
                        <label for="edit-subject-code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="edit-subject-code" name="subject_code" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-subject-name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="edit-subject-name" name="subject_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-subject-type" class="form-label">Subject Type</label>
                        <select class="form-select" id="edit-subject-type" name="subject_type" required></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JS -->
    <script src="../../public/assets/js/get_subject.js"></script>
    <script src="../../public/assets/js/delete_subject.js"></script>
    <script src="../../public/assets/js/admin_change_password.js"></script>
    <script src="../../public/assets/js/add_subject.js"></script>
    <script src="../../public/assets/js/search_subject.js"></script>
    <script src="../../public/assets/js/update_subject_info.js"></script>
    <script src="../../public/assets/js/get_profile_picture.js"></script>

</body>
</html>
