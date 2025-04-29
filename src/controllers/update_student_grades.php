<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Path to config.php in the project root
require dirname(dirname(__DIR__)) . '/config.php';

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    // Check if PDO connection is available
    if (!isset($pdo)) {
        throw new Exception('Database connection not available');
    }
    
    // Get and parse input data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new Exception('Invalid or missing data.');
    }
    
    $updatedGrades = $data['updatedGrades'] ?? [];
    
    // No updates
    if (empty($updatedGrades)) {
        throw new Exception('No updates detected.');
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Update grades for the given grade_id
    $updateStmt = $pdo->prepare("UPDATE grades SET grade = ?, last_modified = CURRENT_TIMESTAMP WHERE grade_id = ?");
    
    foreach ($updatedGrades as $grade) {
        // Ensure grade is a valid number within range
        if (!isset($grade['grade']) || !is_numeric($grade['grade']) || $grade['grade'] < 0 || $grade['grade'] > 100) {
            throw new Exception('Invalid grade value.');
        }
        
        $updateStmt->execute([$grade['grade'], $grade['grade_id']]);
    }
    
    // Commit transaction
    $pdo->commit();
    $response = ['status' => 'success', 'message' => 'Grades updated successfully.'];
    
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log error
    error_log('Error in update student grades: ' . $e->getMessage(), 0);
    $response = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
}

// Return JSON response
echo json_encode($response);
exit;
?>