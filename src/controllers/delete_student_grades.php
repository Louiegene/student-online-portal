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
    
    $deletedGrades = $data['deletedGrades'] ?? [];
    
    // No deletions
    if (empty($deletedGrades)) {
        throw new Exception('No deletions detected.');
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete grades for the given grade_id
    $deleteStmt = $pdo->prepare("DELETE FROM grades WHERE grade_id = ?");
    
    foreach ($deletedGrades as $grade) {
        if (!isset($grade['grade_id'])) {
            continue;
        }
        
        $deleteStmt->execute([$grade['grade_id']]);
    }
    
    // Commit transaction
    $pdo->commit();
    $response = ['status' => 'success', 'message' => 'Grades deleted successfully.'];
    
} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log error
    error_log('Error in delete student grades: ' . $e->getMessage(), 0);
    $response = ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
}

// Return JSON response
echo json_encode($response);
exit;
?>