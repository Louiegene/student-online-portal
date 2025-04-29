<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Path to config.php in the project root
require dirname(dirname(__DIR__)) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        // Check if PDO connection is available
        if (!isset($pdo)) {
            throw new Exception('Database connection not available');
        }
        
        // Get POST data and sanitize input
        $subject_code = trim($_POST['subject_code'] ?? '');
        $subject_name = trim($_POST['subject_name'] ?? '');
        $subject_type = trim($_POST['subject_type'] ?? '');
        
        // Validate inputs
        if (empty($subject_code) || empty($subject_name) || empty($subject_type)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }
        
        // Check if subject already exists
        $pdo->beginTransaction();
        $checkQuery = "SELECT COUNT(*) FROM subjects WHERE subject_code = :subject_code";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([':subject_code' => $subject_code]);
        $existingCount = $checkStmt->fetchColumn();
        
        if ($existingCount > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Subject already exists.']);
            exit;
        }
        
        // Insert new subject
        $insertQuery = "INSERT INTO subjects (subject_code, subject_name, subject_type) VALUES (:subject_code, :subject_name, :subject_type)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            ':subject_code' => $subject_code,
            ':subject_name' => $subject_name,
            ':subject_type' => $subject_type,
        ]);
        
        // Commit transaction
        $pdo->commit();
        echo json_encode(['status' => 'success', 'message' => 'Subject added successfully!']);
        
    } catch (Exception $e) {
        // Rollback transaction if an error occurs
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        
        error_log("Database error: " . $e->getMessage(), 0);
        echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
    }
}
?>