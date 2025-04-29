<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';
header('Content-Type: application/json');

// Check if student_id is provided
if (!isset($_GET['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Student ID is required']);
    exit;
}

$student_id = filter_var($_GET['student_id'], FILTER_SANITIZE_NUMBER_INT);

try {
    // The PDO connection ($pdo) is already established in your config.php
    // No need to create a new connection
    
    // Query to get the student's profile picture from Users table by joining with student_info
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id,
            u.profile_picture
        FROM 
            student_info s
        JOIN 
            Users u ON s.user_id = u.user_id
        WHERE 
            s.student_id = ?
    ");
    
    $stmt->execute([$student_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && isset($result['profile_picture']) && !empty($result['profile_picture'])) {
        echo json_encode([
            'status' => 'success',
            'profile_picture' => $result['profile_picture']
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'profile_picture' => 'user_profile.png' // Default profile picture
        ]);
    }
} catch (PDOException $e) {
    error_log("Error fetching student profile picture: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>