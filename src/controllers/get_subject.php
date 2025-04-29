<?php
session_start();
require __DIR__ . '/../../config.php'; // Include the config.php file
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in as admin.']);
    exit;
}

try {
    // Database connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulated prepared statements
    ]);

    // Initialize variables
    $subjects = [];
    $subject_id = $_GET['id'] ?? ''; // Get the subject ID from the query string
    $search_term = $_GET['search'] ?? ''; // Get the search term from the query string

    // Check if we need to fetch a specific subject by ID
    if (!empty($subject_id)) {
        $sql = "
            SELECT subject_id, subject_code, subject_name, subject_type
            FROM subjects
            WHERE subject_id = :subject_id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['subject_id' => $subject_id]);

        $subjects = $stmt->fetchAll();
    } else {
        // Base query to fetch all subjects
        $sql = "
            SELECT subject_id, subject_code, subject_name, subject_type 
            FROM subjects
        ";

        // Add search condition if search term is provided
        if (!empty($search_term)) {
            $sql .= " WHERE subject_code LIKE :search OR subject_name LIKE :search";
        }

        $stmt = $pdo->prepare($sql);

        // Bind the search term parameter if search is performed
        if (!empty($search_term)) {
            $stmt->execute(['search' => '%' . $search_term . '%']);
        } else {
            $stmt->execute();
        }

        $subjects = $stmt->fetchAll();
    }

} catch (PDOException $e) {
    // Log the error and show a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Unable to fetch subject data at this time. Please try again later.']);
    exit; // Stop further execution if there's a database error
}

// Return subjects (either filtered by search term or by subject ID)
echo json_encode([
    'status' => 'success',
    'subjects' => $subjects
]);
?>
