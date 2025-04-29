<?php
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Pragma: no-cache");

session_start();
require dirname(dirname(__DIR__)) . '/config.php'; // Include the config.php file


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
    $students = [];
    $search_lrn = $_GET['lrn'] ?? ''; // Get the LRN from the query string

    // Base query to fetch student list
    $sql = "
        SELECT
            si.student_id, 
            si.lrn,
            si.first_name,
            si.middle_name,
            si.last_name,
            si.grade_level,
            si.section,
            t.trackname AS trackname,
            s.strandname AS strandname,
            si.user_id
        FROM 
            student_info si
        INNER JOIN 
            Track t ON si.track_id = t.track_id
        INNER JOIN 
            Strand s ON si.strand_id = s.strand_id
    ";

    // Add search condition if LRN is provided
    if (!empty($search_lrn)) {
        $sql .= " WHERE si.lrn LIKE :lrn";
    }

    $stmt = $pdo->prepare($sql);

    // Bind LRN parameter if search is performed
    if (!empty($search_lrn)) {
        $stmt->execute(['lrn' => '%' . $search_lrn . '%']);
    } else {
        $stmt->execute();
    }

    $students = $stmt->fetchAll();

} catch (PDOException $e) {
    // Log the error and show a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Unable to fetch student data at this time. Please try again later.']);
    exit; // Stop further execution if there's a database error
}

// Return all students (no pagination, just search if applicable)
echo json_encode([
    'status' => 'success',
    'students' => $students
]);
?>
