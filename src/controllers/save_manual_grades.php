<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in as admin.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Database connection using your config.php variables
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        // Get admin user ID from session
        $user_id = $_SESSION['user_id'];
        
        // Get and sanitize form data
        $lrn = trim($_POST['lrn'] ?? '');
        $quarter = trim($_POST['quarter'] ?? '');
        $semester = trim($_POST['semester'] ?? '');
        $school_year = trim($_POST['school_year'] ?? '');
        $grade_level = trim($_POST['grade_level'] ?? '');
        $subjects = $_POST['subject'] ?? [];
        $grades = $_POST['grade'] ?? [];
        
        // Validate required fields
        if (empty($lrn) || empty($quarter) || empty($semester) || empty($school_year) || empty($grade_level) ||
            empty($subjects) || empty($grades) || count($subjects) !== count($grades)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required and subject/grade counts must match.']);
            exit;
        }
        
        // Validate LRN format (assuming 12-digit number)
        if (!preg_match('/^\d{12}$/', $lrn)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid LRN format. Must be a 12-digit number.']);
            exit;
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Get the student user_id based on the LRN
        $studentQuery = "SELECT user_id FROM student_info WHERE lrn = :lrn";
        $studentStmt = $pdo->prepare($studentQuery);
        $studentStmt->execute([':lrn' => $lrn]);
        $studentUserId = $studentStmt->fetchColumn();

        if (!$studentUserId) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Student with this LRN does not exist.']);
            exit;
        }
        
        // Insert or update grades for each subject
        $insertCount = 0;
        $updateCount = 0;
        $errors = [];
        
        // Prepare check query
        $checkQuery = "SELECT grade_id FROM grades 
                       WHERE lrn = :lrn AND subject_id = :subject_id 
                       AND school_year = :school_year AND semester = :semester AND quarter = :quarter";
        $checkStmt = $pdo->prepare($checkQuery);
        
        // Prepare insert query
        $insertQuery = "INSERT INTO grades (user_id, lrn, subject_id, grade, school_year, semester, quarter, grade_level) 
                        VALUES (:user_id, :lrn, :subject_id, :grade, :school_year, :semester, :quarter, :grade_level)";
        $insertStmt = $pdo->prepare($insertQuery);
        
        // Prepare update query
        $updateQuery = "UPDATE grades SET grade = :grade, user_id = :user_id 
                        WHERE grade_id = :grade_id";
        $updateStmt = $pdo->prepare($updateQuery);
        
        // Process each subject-grade pair
        for ($i = 0; $i < count($subjects); $i++) {
            $subject_id = trim($subjects[$i]);
            $grade = trim($grades[$i]);
            
            // Validate subject and grade
            if (empty($subject_id) || !is_numeric($grade) || $grade < 0 || $grade > 100) {
                $errors[] = "Invalid data for subject #" . ($i + 1);
                continue;
            }
            
            // Check if grade record already exists
            $checkStmt->execute([
                ':lrn' => $lrn,
                ':subject_id' => $subject_id,
                ':school_year' => $school_year,
                ':semester' => $semester,
                ':quarter' => $quarter
            ]);
            $existingGradeId = $checkStmt->fetchColumn();
            
            if ($existingGradeId) {
                // Update existing grade
                $updateStmt->execute([
                    ':grade' => $grade,
                    ':user_id' => $studentUserId,
                    ':grade_id' => $existingGradeId
                ]);
                $updateCount++;
            } else {
                // Insert new grade
                $insertStmt->execute([
                    ':user_id' => $studentUserId,
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
        
        // Check if there were any successful operations
        if ($insertCount === 0 && $updateCount === 0) {
            $pdo->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'No grades were saved.',
                'errors' => $errors
            ]);
            exit;
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Return success response
        $message = "";
        if ($insertCount > 0) {
            $message .= "$insertCount new grade(s) inserted. ";
        }
        if ($updateCount > 0) {
            $message .= "$updateCount existing grade(s) updated.";
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => $message,
            'errors' => $errors
        ]);
        
    } catch (PDOException $e) {
        // Rollback and log error
        if (isset($pdo)) {
            $pdo->rollBack();
        }
        error_log("Database error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => 'An error occurred while saving grades. Please try again later.'
        ]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>