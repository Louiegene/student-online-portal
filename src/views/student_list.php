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
        <h1 style="margin: 0; text-align: center; font-size: 24px;"><strong>Student List</strong></h1>
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
            <button id="addStudentBtn" class="btn btn-primary">Add Student</button>
        </div>
    </div>

    <!-- Optional Loading Message -->
    <p id="loadingMessage" style="text-align: center; display: none;">Loading students...</p>

    <!-- Responsive Table Wrapper -->
    <div style="width: 90%; max-width: 1200px; margin: 0 auto; height: 600px; overflow-y: auto;">
    <table id="studentTable" class="table table-striped" style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: center;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">LRN</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Name</th>
                <th style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 1; padding: 8px; border: 1px solid #dee2e6;">Section</th>
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
        
    <!-- Popup Modal for Add Student -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addStudentModalLabel"><strong>Add Student</strong></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" tabindex="0"></button>
            </div>
            <form id="addStudentForm">
                <div class="modal-body">
                    <!-- User Table Fields -->
                     <h5><strong>School Year</strong></h5>
                     <div class="row mb-3">
                        <label for="school_year">School Year</label>
                        <select class="form-select" id="school_year" name="school_year" required>
                            <?php
                            $currentYear = date('Y');
                            for ($i = 0; $i < 5; $i++) {
                                $year = $currentYear - $i;
                                $nextYear = $year + 1;
                                echo "<option value=\"$year-$nextYear\">$year-$nextYear</option>";
                            }
                            ?>
                        </select>
                        <!-- <input type="text" class="form-control" id="school_year" name="school_year" placeholder="yyyy-yyyy" required> -->
                    </div class="row mb-3">
                    <h5><strong>User Information</strong></h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                        <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                            <span id="username-feedback" class="text-sm"></span>
                            <small id="username-feedback" class="form-text"></small>
                        </div>
                        <div class="col-md-6">
                        <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <small id="email-feedback" class="form-text"></small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="hidden" id="password" name="password">
                        </div>
                    </div>

                    <!-- Student Info Add Student Modal -->
                    <h5><strong>Student Information</strong></h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="enrollment_date" class="form-label">Enrollment Date</label>
                            <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lrn" class="form-label">Learner Reference Number</label>
                            <input type="text" class="form-control" id="lrn" name="lrn" maxlength="12" placeholder="Enter your 12-digit LRN" required>
                            <small id="lrnError" class="text-danger d-none">LRN must be numbers only.</small>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name (Optional)</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name">
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="birthdate" class="form-label">Birthday</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                        </div>
                        <div class="col-md-4">
                            <label for="genderSelect" class="form-label">Gender</label>
                            <select class="form-select" id="genderSelect" name="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select class="form-select" id="grade_level" name="grade_level" required>
                                <option value="" disabled selected>Select Grade</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <!--<input type="text" class="form-control" id="grade_level" name="grade_level" placeholder="Grade Level" required>-->
                        </div>
                        <div class="col-md-4">
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control" id="section" name="section" placeholder="Section" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="track" class="form-label">Select Track</label>
                            <label for="track" class="form-label">Select Track</label>
                            <select id="track" name="track" class="form-select">
                            <option value="" disabled selected>Select Track</option>
                            <!-- Dynamic Track options will go here -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="strand" class="form-label">Select Strand</label>
                            <select id="strand" name="strand" class="form-select">
                                <option value="" disabled selected>Select Strand</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="specific_strand" class="form-label">Specific Strand(if TVL)</label>
                            <input type="text" class="form-control" id="specific_strand" name="specific_strand" placeholder="Enter Specific Strand">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End of Student Info Add Student Modal -->

<!-- View Student Modal -->
<div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewStudentModalLabel">Student Information</h5>
        <!-- Close button for the modal -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Profile picture -->
        <div class="text-center mb-3">
          <img id="studentProfilePic" src="../../public/assets/images/user_profile.png" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
        </div>
        <!-- Info List -->
        <ul class="list-group">
          <li class="list-group-item"><strong>LRN:</strong> <span id="info-lrn"></span></li>
          <li class="list-group-item"><strong>Name:</strong> <span id="info-name"></span></li>
          <li class="list-group-item"><strong>Gender:</strong> <span id="info-gender"></span></li>
          <li class="list-group-item"><strong>Birthdate:</strong> <span id="info-birthdate"></span></li>
          <li class="list-group-item"><strong>Enrollment Date:</strong> <span id="info-enrollment"></span></li>
          <li class="list-group-item"><strong>Enrollment Status:</strong> <span id="info-status"></span></li>
          <li class="list-group-item"><strong>Grade Level & Section:</strong> <span id="info-grade-section"></span></li>
          <li class="list-group-item"><strong>Track:</strong> <span id="info-track"></span></li>
          <li class="list-group-item"><strong>Strand:</strong> <span id="info-strand"></span></li>
          <li class="list-group-item"><strong>Specific Strand:</strong> <span id="info-specific-strand"></span></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End of View Student Modal -->

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

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="editStudentForm">
        <div class="modal-header">
          <h5 class="modal-title" id="editStudentModalLabel">Edit Student Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <input type="hidden" id="edit-student-id" name="student_id">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="edit-lrn" class="form-label">LRN</label>
              <input type="text" class="form-control" id="edit-lrn" name="lrn" required>
            </div>
            <div class="col-md-6">
              <label for="edit-enrollment-status" class="form-label">Enrollment Status</label>
              <select class="form-select" id="edit-enrollment-status" name="enrollment_status" required>
                <option value="Enrolled">Enrolled</option>
                <option value="Not Enrolled">Dropped</option>
                <option value="Dropped">No Longer Participating (NLP)</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="edit-first-name" class="form-label">First Name</label>
              <input type="text" class="form-control" id="edit-first-name" name="first_name" required>
            </div>
            <div class="col-md-4">
              <label for="edit-middle-name" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="edit-middle-name" name="middle_name">
            </div>
            <div class="col-md-4">
              <label for="edit-last-name" class="form-label">Last Name</label>
              <input type="text" class="form-control" id="edit-last-name" name="last_name" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4">
              <label for="edit-track" class="form-label">Track</label>
              <select class="form-select" id="edit-track" name="track_id" required></select>
            </div>
            <div class="col-md-4">
              <label for="edit-strand" class="form-label">Strand</label>
              <select class="form-select" id="edit-strand" name="strand_id"></select>
            </div>
            <div class="col-md-4">
              <label for="edit-specific-strand" class="form-label">Specific Strand</label>
              <input type="text" class="form-control" id="edit-specific-strand" name="specific_strand">
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--End of Edit Student Modal-->
        
        <!--Scripts-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- jQuery (if needed) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Your Custom JS -->
        <script src="../../public/assets/js/get_profile_picture.js"></script>
        <script src="../../public/assets/js/get_student.js"></script>
        <script src="../../public/assets/js/delete_student.js"></script>
        <script src="../../public/assets/js/view_student_info.js"></script>
        <script src="../../public/assets/js/admin_change_password.js"></script>
        <script src="../../public/assets/js/update_student_info.js"></script>
        <script src="../../public/assets/js/add_student.js"></script>
        <script src="../../public/assets/js/search_student.js"></script>
        <script src="../../public/assets/js/check_username_email.js"></script>
        <!-- <script src="../../public/assets/js/view_student_profile_pic.js"></script> -->
        

        <script>
        // Generate password from birthday
        document.getElementById('birthdate').addEventListener('change', function () {
            const birthday = this.value; // Format: YYYY-MM-DD
            if (birthday) {
                const parts = birthday.split('-'); // Split into [YYYY, MM, DD]
                const password = parts[1] + parts[2] + parts[0]; // Rearrange to MMDDYYYY
                document.getElementById('password').value = password;
            }
        });

        // Validate LRN (12 digits)
        document.getElementById('lrn').addEventListener('input', function () {
            const lrn = this.value;
            if (lrn.length > 12) {
                this.value = lrn.slice(0, 12); // Limit to 12 digits
            }
        });

        // Handle form submission
        document.getElementById('addStudentForm').addEventListener('submit', function (e) {
            e.preventDefault();
            // Perform AJAX or form submission logic here
            //alert('Form submitted!');
        });
        </script>

        <!--Selection of Track and Strand Script Database-->
        <script>
        $(document).ready(function() {
            // Fetch all tracks and strands once on page load
            var allStrands = [];

            $.ajax({
                url: '../../src/controllers/get_strands.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Strands Response:', response);
                    // Store all strands globally
                    allStrands = response.strands;

                    // Populate the track dropdown
                    var trackSelect = $('#track');
                    $.each(response.tracks, function(index, track) {
                        trackSelect.append(new Option(track.trackname, track.track_id));
                    });

                    // Enable the track dropdown
                    trackSelect.prop('disabled', false);
                },
                error: function() {
                    alert('Error fetching track and strand options.');
                }
            });

            // When the track changes, filter the strands based on the selected track
            $('#track').change(function() {
                var track_id = $(this).val();
                console.log('Selected Track ID:', track_id); // Debugging the selected track ID
                
                // Reset the strand dropdown
                var strandSelect = $('#strand');
                strandSelect.html('<option value="" disabled selected>Select Strand</option>');
                strandSelect.prop('disabled', true); // Disable strand dropdown initially

                if (track_id) {
                    // Filter the strands based on the selected track_id
                    var filteredStrands = allStrands.filter(function(strand) {
                        return strand.track_id == track_id;
                    });

                    console.log('Filtered Strands:', filteredStrands); // Debugging the filtered strands

                    // Populate the strand dropdown if there are filtered strands
                    if (filteredStrands.length > 0) {
                        $.each(filteredStrands, function(index, strand) {
                            strandSelect.append(new Option(strand.strandname, strand.strand_id));
                        });
                        strandSelect.prop('disabled', false); // Enable the strand dropdown
                    } else {
                        // If no strands available for the selected track
                        strandSelect.append(new Option('No strands available', '', true, true));
                    }
                }
            });
        });
        </script>

        <!--Script for validating some fields -->
        <script>
        document.getElementById("addStudentForm").onsubmit = function(event) {
            const gradeLevel = document.getElementById("grade_level").value;
            const section = document.getElementById("section").value;
            const track = document.getElementById("track").value;
            const strand = document.getElementById("strand").value;

            let formValid = true;
            if (!gradeLevel) {
                document.getElementById("grade_level").classList.add("is-invalid");
                formValid = false;
            } else {
                document.getElementById("grade_level").classList.remove("is-invalid");
            }
            
            if (!section) {
                document.getElementById("section").classList.add("is-invalid");
                formValid = false;
            } else {
                document.getElementById("section").classList.remove("is-invalid");
            }

            if (!track) {
                document.getElementById("track").classList.add("is-invalid");
                formValid = false;
            } else {
                document.getElementById("track").classList.remove("is-invalid");
            }

            if (!strand) {
                document.getElementById("strand").classList.add("is-invalid");
                formValid = false;
            } else {
                document.getElementById("strand").classList.remove("is-invalid");
            }

            if (!formValid) {
                event.preventDefault(); // Prevent form submission
            }
        };
        </script>

        <!--Script for lrn must be numeric -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const lrnInput = document.getElementById('lrn');
            const lrnError = document.getElementById('lrnError');

            lrnInput.addEventListener('keypress', function (e) {
                const char = String.fromCharCode(e.which);
                if (!/^\d$/.test(char)) {
                    e.preventDefault();
                    lrnError.classList.remove('d-none'); // Show the error
                    setTimeout(() => {
                        lrnError.classList.add('d-none'); // Hide after 2 seconds
                    }, 2000);
                }
            });

            // handle paste
            lrnInput.addEventListener('paste', function (e) {
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                if (!/^\d+$/.test(paste)) {
                    e.preventDefault();
                    lrnError.classList.remove('d-none');
                    setTimeout(() => {
                        lrnError.classList.add('d-none');
                    }, 2000);
                }
            });
        });

        </script>

    </body>
</html>