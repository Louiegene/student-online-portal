<?php
session_start();
// get_studentid_by_lrn.php
// This script will look up a student's user_id based on their LRN

// Initialize response array
$response = [
    'status' => 'error',
    'message' => 'Unable to retrieve student information.',
    'user_id' => null
];

// Check if LRN was provided
if (!isset($_POST['lrn']) || empty($_POST['lrn'])) {
    $response['message'] = 'No LRN provided.';
    echo json_encode($response);
    exit;
}

// Sanitize input
$lrn = trim($_POST['lrn']);

try {
    // Connect to database using environment variables with fallbacks
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('DB_NAME') ?: 'student_portal';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: 3306;
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    // Create PDO connection
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Prepare SQL to find user_id based on LRN
    $sql = "SELECT user_id FROM student_info WHERE lrn = :lrn LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':lrn', $lrn, PDO::PARAM_STR);
    $stmt->execute();
    
    // Check if student was found
    if ($stmt->rowCount() > 0) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $response['status'] = 'success';
        $response['message'] = 'Student found.';
        $response['user_id'] = $result['user_id'];
    } else {
        $response['message'] = 'No student found with the provided LRN.';
    }
} catch (PDOException $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
    error_log('Database error in get_studentid_by_lrn.php: ' . $e->getMessage());
} finally {
    // Close connection
    $conn = null;
}

// Return response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>