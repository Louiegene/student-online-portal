<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Student Grade Page - Limay Senior High School</title>
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
        <h1 style="margin: 0; text-align: center; font-size: 24px;"><strong>Student Grades</strong></h1>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px; flex-wrap: wrap;">
            <form method="get" action="" style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px;">
                <label for="lrn" style="font-size: 16px; font-weight: bold;">Search</label>
                <input 
                    type="text" 
                    id="searchInput" 
                    name="student" 
                    value="" 
                    style="padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; width: 100%; max-width: 300px;"
                    placeholder="Search by LRN or Name"
                >
            </form>
            <button 
                id="addStudentGradeBtn" 
                class="btn btn-primary" 
                data-bs-toggle="modal" 
                data-bs-target="#addStudentGradeModal">
                Add Student Grade
            </button>

        </div>
    </div>

<!-- Optional Loading Message -->
<p id="loadingMessage" style="text-align: center; display: none;">Loading students...</p>

<!-- Table to Display Student Grade List -->
<div style="width: 90%; max-width: 1200px; margin: 0 auto; height: 600px; overflow-y: auto;">
    <table id="studentTable" class="table table-striped" style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: center;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">LRN</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Name</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Grade</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Track</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Strand</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Actions</th>
            </tr>
        </thead>
        <tbody id="studentTableBody">
            <!-- Rows injected here -->
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
        
<!-- Add Student Grade Modal -->
<div class="modal fade" id="addStudentGradeModal" tabindex="-1" aria-labelledby="addStudentGradeLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="addStudentGradeLabel"><strong>Add Student Grade</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="gradeTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">Manual Entry</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="csv-tab" data-bs-toggle="tab" data-bs-target="#csv" type="button" role="tab">Import from CSV</button>
          </li>
        </ul>

        <div class="tab-content pt-3">
          <!-- Manual Grade Entry Tab -->
          <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
            <form id="manualGradeForm">
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="manual_lrn" class="form-label">LRN</label>
                  <input type="text" class="form-control" id="manual_lrn" name="lrn" required>
                </div>
                <div class="col-md-4">
                  <label for="manual_quarter" class="form-label">Quarter</label>
                  <select class="form-select" id="manual_quarter" name="quarter" required>
                    <option value="1st">1st Quarter</option>
                    <option value="2nd">2nd Quarter</option>
                    <option value="3rd">3rd Quarter</option>
                    <option value="4th">4th Quarter</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="manual_semester" class="form-label">Semester</label>
                  <select class="form-select" id="manual_semester" name="semester" required>
                    <option value="1st">1st Semester</option>
                    <option value="2nd">2nd Semester</option>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="manual_school_year" class="form-label">School Year</label>
                  <select class="form-select" id="manual_school_year" name="school_year" required>
                    <?php
                      $currentYear = date('Y');
                      for ($i = 0; $i < 5; $i++) {
                          $year = $currentYear - $i;
                          $nextYear = $year + 1;
                          echo "<option value=\"$year-$nextYear\">$year-$nextYear</option>";
                      }
                    ?>
                  </select>
                </div>

                <div class="col-md-4">
                  <label for="manual_grade_level" class="form-label">Grade Level</label>
                  <select class="form-select" id="manual_grade_level" name="grade_level" required>
                    <option value="" disabled selected>Select Grade Level</option>
                    <option value="11">Grade 11</option>
                    <option value="12">Grade 12</option>
                  </select>
                </div>
              </div>

              <div id="gradeFields">
                <div class="row mb-3 grade-entry">
                  <div class="col-md-7">
                    <label class="form-label">Subject</label>
                    <select class="form-select subject-dropdown" name="subject[]" required>
                      <option value="" disabled selected>Loading subjects...</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label">Grade</label>
                    <input type="number" class="form-control" name="grade[]" min="0" max="100" step="0.01" required>
                  </div>
                  <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-field">&times;</button>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <button type="button" class="btn btn-outline-primary" id="addFieldBtn">
                  <i class="fas fa-plus"></i> Add Another Subject
                </button>
              </div>
            </form>
          </div>

          <!-- CSV Upload Tab -->
          <div class="tab-pane fade" id="csv" role="tabpanel" aria-labelledby="csv-tab">
            <form id="bulkUploadForm" enctype="multipart/form-data">
              <div class="alert alert-info">
                <p><strong>Instructions:</strong></p>
                <ol>
                  <li>Download the template CSV file by clicking the button below.</li>
                  <li>Fill in the LRN and grades for each student and subject.</li>
                  <li>Save the file and upload it using the file selector.</li>
                  <li>Select the quarter, semester, and school year for the grades.</li>
                  <li>Click "Upload Grades" to process the file.</li>
                </ol>
              </div>
              
              <div class="mb-3">
                <a href="../../src/controllers/bulk_upload_grades.php?download=template" class="btn btn-outline-secondary">
                  <i class="fas fa-download"></i> Download Template
                </a>
              </div>

              <div class="mb-3">
                <label for="csv_file" class="form-label">CSV File</label>
                <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="csv_quarter" class="form-label">Quarter</label>
                    <select class="form-select" id="csv_quarter" name="quarter" required>
                      <option value="1st">1st Quarter</option>
                      <option value="2nd">2nd Quarter</option>
                      <option value="3rd">3rd Quarter</option>
                      <option value="4th">4th Quarter</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="csv_semester" class="form-label">Semester</label>
                    <select class="form-select" id="csv_semester" name="semester" required>
                      <option value="1st">1st Semester</option>
                      <option value="2nd">2nd Semester</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="csv_school_year" class="form-label">School Year</label>
                    <select class="form-select" id="csv_school_year" name="school_year" required>
                      <?php
                        $currentYear = date('Y');
                        for ($i = 0; $i < 5; $i++) {
                            $year = $currentYear - $i;
                            $nextYear = $year + 1;
                            echo "<option value=\"$year-$nextYear\">$year-$nextYear</option>";
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="manual_grade_level" class="form-label">Grade Level</label>
                    <select class="form-select" id="manual_grade_level" name="grade_level" required>
                      <option value="" disabled selected>Select Grade Level</option>
                      <option value="11">Grade 11</option>
                      <option value="12">Grade 12</option>
                    </select>
                  </div>
              </div>
              </div>
              <div id="uploadResult" class="mt-3" style="display: none;">
                <div class="alert" id="uploadResultMessage"></div>
                <div id="uploadErrors" style="max-height: 200px; overflow-y: auto;"></div>
              </div>

              <div class="mt-3">
                <button type="button" class="btn btn-primary" id="uploadGradesBtn">Upload Grades</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="manualGradeForm" class="btn btn-primary">Save Grades</button>
      </div>

    </div>
  </div>
</div>
<!-- End of add Student Grade modal -->

<!-- View Student Grade Modal -->
<div class="modal fade" id="viewStudentGradeModal" tabindex="-1" aria-labelledby="viewStudentGradeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="viewStudentGradeModalLabel"><strong>Student Information & Grades</strong></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <!-- Body -->
      <div class="modal-body">
        <!-- Student Info -->
        <ul class="list-group list-group-flush mb-4">
          <li class="list-group-item"><strong>Full Name:</strong> <span id="info-name"></span></li>
          <li class="list-group-item"><strong>Learner Reference Number:</strong> <span id="info-lrn"></span></li>
          <li class="list-group-item"><strong>Track:</strong> <span id="info-track"></span></li>
          <li class="list-group-item"><strong>Strand:</strong> <span id="info-strand"></span></li>
          <li class="list-group-item"><strong>Grade Level & Section:</strong> <span id="info-grade-level"></span> - <span id="info-section"></span></li>
        </ul>
        <!-- Dropdowns in row -->
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="quarterSelect" class="form-label"><strong>Select Quarter</strong></label>
            <select class="form-select" id="quarterSelect">
              <option value="1st">1st Quarter</option>
              <option value="2nd">2nd Quarter</option>
              <option value="3rd">3rd Quarter</option>
              <option value="4th">4th Quarter</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="gradeLevelSelect" class="form-label"><strong>Select Grade Level</strong></label>
            <select id="gradeLevelSelect" class="form-select">
              <option value="11">Grade 11</option>
              <option value="12">Grade 12</option>
            </select>
          </div>
        </div>
        <!-- Grades Table -->
        <h6 class="mb-3"><strong>Grades</strong></h6>
        <form id="editGradesForm">
          <div class="table-responsive">
            <table class="table table-striped table-bordered text-center align-middle">
              <thead class="table-light sticky-top">
                <tr>
                  <th>Subject</th>
                  <th>Quarter</th>
                  <th>Grade</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="gradesTableBody">
                <!-- Dynamically filled - Example row structure below -->
              </tbody>
            </table>
          </div>
        </form>
      </div>
      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="editGradesForm" class="btn btn-success">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Modal -->


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
        
        <!--Scripts-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- jQuery (if needed) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Custom JS -->
        <script src="../../public/assets/js/get_profile_picture.js"></script>
        <script src="../../public/assets/js/get_student_list_for_grades_view.js"></script>
        <!-- <script src="../../public/assets/js/get_profile_picture.js"></script> -->
        <script src="../../public/assets/js/admin_change_password.js"></script>
        <script src="../../public/assets/js/add_grade_modal.js"></script>
        <script src="../../public/assets/js/viewmodal_grades_management.js"></script> 

    </body>
</html>