<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';

// Set content type to JSON
header('Content-Type: application/json');

// Initialize response array
$response = array(
    'success' => false,
    'message' => '',
    'first_name' => '',
    'last_name' => '',
    'lrn' => '',
    'trackname' => '',  // Correct field name for track
    'strandname' => ''  // Correct field name for strand
);

try {
    // Check if user_id is provided via GET
    if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
        // If no user_id provided in GET, check if it exists in the session
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            throw new Exception('User ID is required');
        }
        $userId = $_SESSION['user_id'];
    } else {
        $userId = $_GET['user_id'];
    }
    
    // Validate and sanitize user_id
    $userId = filter_var($userId, FILTER_VALIDATE_INT);
    if ($userId === false) {
        throw new Exception('Invalid User ID');
    }

    // Ensure PDO connection from config
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }

    // Prepare SQL query to fetch student information, track, and strand
    $sql = "SELECT 
                s.first_name, 
                s.last_name, 
                s.lrn, 
                t.trackname, 
                st.strandname
            FROM 
                student_info s
            LEFT JOIN 
                track t ON s.track_id = t.track_id
            LEFT JOIN 
                strand st ON s.strand_id = st.strand_id
            WHERE 
                s.user_id = :user_id;
            ";
    
    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Check if student exists
    if ($stmt->rowCount() === 0) {
        throw new Exception('Student not found');
    }
    
    // Fetch student data
    $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Update response with student data
    $response['success'] = true;
    $response['first_name'] = $studentData['first_name'];
    $response['last_name'] = $studentData['last_name'];
    $response['lrn'] = $studentData['lrn'];
    $response['trackname'] = $studentData['trackname'];  // Corrected field name for track
    $response['strandname'] = $studentData['strandname'];  // Corrected field name for strand

} catch (Exception $e) {
    // Handle errors and add the error message to response
    $response['message'] = $e->getMessage();
}

// Return the JSON response
echo json_encode($response);
?>
