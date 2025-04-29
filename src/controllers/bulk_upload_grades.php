<?php
session_start();
require __DIR__ . '/../../config.php'; // Include your config file

// Check if user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    if (isset($_GET['download']) && $_GET['download'] === 'template') {
        // Still handle unauthorized requests gracefully for download attempts
        header('Content-Type: text/plain');
        echo "Unauthorized access. Please log in as admin.";
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in as admin.']);
    }
    exit;
}

// Function to generate CSV template for download
function generateCsvTemplate() {
    global $host, $port, $dbname, $username, $password;
    
    try {
        // Connect to database
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        // Get all subjects for the header
        $subjectQuery = "SELECT subject_id, subject_code, subject_name FROM subjects ORDER BY subject_code";
        $subjects = $pdo->query($subjectQuery)->fetchAll();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="grade_upload_template.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Write CSV header row
        $header = ['LRN'];
        $subjectIds = [];
        
        foreach ($subjects as $subject) {
            $header[] = $subject['subject_code'] . ' - ' . $subject['subject_name'];
            $subjectIds[] = $subject['subject_id'];
        }
        
        fputcsv($output, $header);
        
        // Write a comment row explaining the format
        $comment = ['# Enter student LRN in column A, then grades (0-100) in subject columns'];
        fputcsv($output, $comment);
        
        // Write another comment row about LRN format
        $lrnNote = ['# IMPORTANT: LRN must be a 12-digit number. If using Excel, format the LRN column as text before entering values.'];
        fputcsv($output, $lrnNote);
        
        // Write the hidden subject IDs row (will be used by the import script)
        $idRow = ['SUBJECT_IDS'];
        foreach ($subjectIds as $id) {
            $idRow[] = $id;
        }
        fputcsv($output, $idRow);
        
        // Get a list of students with their LRNs for the template
        $studentQuery = "SELECT lrn FROM student_info ORDER BY lrn LIMIT 1";
        $students = $pdo->query($studentQuery)->fetchAll();
        
        // If we have students, add them to the template
        if (!empty($students)) {
            foreach ($students as $student) {
                $row = ['"' . $student['lrn'] . '"']; // Add quotes to force text format
                for ($i = 0; $i < count($subjects); $i++) {
                    $row[] = ''; // Empty grade cells
                }
                fputcsv($output, $row, ',', '"', '\\', false); // Use custom fputcsv to preserve the quotes
            }
        } else {
            $sample = ['"123456789012"']; // Sample LRN with quotes to force text format
            for ($i = 0; $i < count($subjects); $i++) {
                $sample[] = ''; // Empty grade cells
            }
            fputcsv($output, $sample, ',', '"', '\\', false); // Use custom fputcsv to preserve the quotes
        }
        
        // Close output stream
        fclose($output);
        exit;
        
    } catch (PDOException $e) {
        header('Content-Type: text/plain');
        echo "Error generating template: " . $e->getMessage();
        exit;
    }
}

// Function to process uploaded CSV file
function processUploadedCsv() {
    global $host, $port, $dbname, $username, $password;
    
    header('Content-Type: application/json');
    
    // Check if we have all required parameters
    if (!isset($_POST['quarter']) || !isset($_POST['semester']) || !isset($_POST['school_year']) || !isset($_POST['grade_level'])) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Missing required parameters (quarter, semester, school year, or grade level)'
        ]);
        exit;
    }
    
    // Check if file was uploaded
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $errorMsg = 'No file uploaded or upload error occurred';
        if (isset($_FILES['csv_file'])) {
            switch($_FILES['csv_file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errorMsg = 'The uploaded file exceeds the maximum file size limit';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errorMsg = 'The file was only partially uploaded';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errorMsg = 'No file was uploaded';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    $errorMsg = 'Server error occurred during upload';
                    break;
            }
        }
        echo json_encode(['status' => 'error', 'message' => $errorMsg]);
        exit;
    }
    
    // Validate file type
    $fileExt = strtolower(pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION));
    if ($fileExt !== 'csv') {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Invalid file type. Please upload a CSV file.'
        ]);
        exit;
    }
    
    // Sanitize input data
    $quarter = trim($_POST['quarter']);
    $semester = trim($_POST['semester']);
    $school_year = trim($_POST['school_year']);
    $grade_level = trim($_POST['grade_level']);
    $admin_user_id = $_SESSION['user_id']; // This is the admin's user_id
    
    try {
        // Connect to database
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Open uploaded file
        $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
        if (!$handle) {
            throw new Exception('Could not open the uploaded file');
        }
        
        // Read header row
        $headerRow = fgetcsv($handle);
        if (!$headerRow) {
            throw new Exception('Empty or invalid CSV file');
        }
        
        // Skip comment rows if they exist
        $nextRow = fgetcsv($handle);
        while ($nextRow && (substr($nextRow[0], 0, 1) === '#' || empty($nextRow[0]))) {
            $nextRow = fgetcsv($handle);
        }
        
        // Get subject IDs
        $subjectIds = [];
        if ($nextRow && $nextRow[0] === 'SUBJECT_IDS') {
            // This is the subject IDs row
            for ($i = 1; $i < count($nextRow); $i++) {
                $subjectIds[] = $nextRow[$i];
            }
            $nextRow = fgetcsv($handle); // Move to the next row
        } else {
            throw new Exception('CSV format is invalid. Missing subject ID row. Please use the downloaded template.');
        }
        
        // Prepare statements
        $checkStudentStmt = $pdo->prepare("SELECT COUNT(*) FROM student_info WHERE lrn = :lrn");
        $getStudentUserIdStmt = $pdo->prepare("SELECT user_id FROM student_info WHERE lrn = :lrn");
        $checkGradeStmt = $pdo->prepare(
            "SELECT grade_id FROM grades 
             WHERE lrn = :lrn AND subject_id = :subject_id 
             AND school_year = :school_year AND semester = :semester AND quarter = :quarter
             AND grade_level = :grade_level"
        );
        $insertGradeStmt = $pdo->prepare(
            "INSERT INTO grades (user_id, lrn, subject_id, grade, school_year, semester, quarter, grade_level) 
             VALUES (:user_id, :lrn, :subject_id, :grade, :school_year, :semester, :quarter, :grade_level)"
        );
        $updateGradeStmt = $pdo->prepare(
            "UPDATE grades SET grade = :grade, user_id = :user_id WHERE grade_id = :grade_id"
        );
        
        // Process data rows
        $rowCount = 0;
        $insertCount = 0;
        $updateCount = 0;
        $errorRows = [];
        
        while ($nextRow !== false) {
            $rowCount++;
            
            // Skip empty rows
            if (empty($nextRow[0])) {
                $nextRow = fgetcsv($handle);
                continue;
            }
            
            $lrn = trim($nextRow[0]);
            // Remove any quotes that might have been added
            $lrn = str_replace('"', '', $lrn);
            
            // Validate LRN format
            if (!preg_match('/^\d{12}$/', $lrn)) {
                $errorRows[] = "Row $rowCount: Invalid LRN format ($lrn). Must be a 12-digit number.";
                $nextRow = fgetcsv($handle);
                continue;
            }
            
            // Check if student exists
            $checkStudentStmt->execute([':lrn' => $lrn]);
            if ($checkStudentStmt->fetchColumn() === 0) {
                $errorRows[] = "Row $rowCount: Student with LRN $lrn does not exist.";
                $nextRow = fgetcsv($handle);
                continue;
            }
            
            // Get the student's user_id
            $getStudentUserIdStmt->execute([':lrn' => $lrn]);
            $student_user_id = $getStudentUserIdStmt->fetchColumn();
            
            if (!$student_user_id) {
                $errorRows[] = "Row $rowCount: Could not find user_id for student with LRN $lrn.";
                $nextRow = fgetcsv($handle);
                continue;
            }
            
            // Process each subject grade
            for ($i = 0; $i < count($subjectIds); $i++) {
                $index = $i + 1; // Columns are offset by 1 (first column is LRN)
                
                // Skip if grade is empty
                if (!isset($nextRow[$index]) || $nextRow[$index] === '') {
                    continue;
                }
                
                $grade = trim($nextRow[$index]);
                $subject_id = $subjectIds[$i];
                
                // Validate grade
                if (!is_numeric($grade) || $grade < 0 || $grade > 100) {
                    $errorRows[] = "Row $rowCount, Subject $subject_id: Invalid grade value ($grade). Must be between 0 and 100.";
                    continue;
                }
                
                // Check if grade record already exists
                $checkGradeStmt->execute([
                    ':lrn' => $lrn,
                    ':subject_id' => $subject_id,
                    ':school_year' => $school_year,
                    ':semester' => $semester,
                    ':quarter' => $quarter,
                    ':grade_level' => $grade_level
                ]);
                $existingGradeId = $checkGradeStmt->fetchColumn();
                
                if ($existingGradeId) {
                    // Update existing grade
                    $updateGradeStmt->execute([
                        ':grade' => $grade,
                        ':user_id' => $student_user_id, // Student's user_id, not admin's
                        ':grade_id' => $existingGradeId
                    ]);
                    $updateCount++;
                } else {
                    // Insert new grade
                    $insertGradeStmt->execute([
                        ':user_id' => $student_user_id, // Student's user_id, not admin's
                        ':lrn' => $lrn,
                        ':subject_id' => $subject_id,
                        ':grade' => $grade,
                        ':school_year' => $school_year,
                        ':semester' => $semester,
                        ':quarter' => $quarter,
                        ':grade_level' => $grade_level
                    ]);
                    $insertCount++;
                }
            }
            
            $nextRow = fgetcsv($handle);
        }
        
        // Close file handle
        fclose($handle);
        
        // Check if any grades were processed
        if ($insertCount === 0 && $updateCount === 0) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'No valid grades were found in the CSV file.',
                'errors' => $errorRows
            ]);
            exit;
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Success response
        $message = "CSV processed successfully. ";
        if ($insertCount > 0) {
            $message .= "$insertCount new grade(s) inserted. ";
        }
        if ($updateCount > 0) {
            $message .= "$updateCount existing grade(s) updated. ";
        }
        if (count($errorRows) > 0) {
            $message .= count($errorRows) . " error(s) encountered.";
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'stats' => [
                'inserted' => $insertCount,
                'updated' => $updateCount,
                'errors' => count($errorRows)
            ],
            'errors' => $errorRows
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction if an error occurred
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("CSV upload error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while processing the CSV: ' . $e->getMessage()
        ]);
    }
}

// Main logic: determine what action to take
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['download']) && $_GET['download'] === 'template') {
    // Generate and download CSV template
    generateCsvTemplate();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process CSV upload
    processUploadedCsv();
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or action']);
}
?>