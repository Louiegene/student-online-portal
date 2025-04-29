<?php
// get_grades.php
session_start();
require dirname(dirname(__DIR__)) . '/config.php'; // Include the config.php for database connection

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => '',
    'grades' => []
];

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User ID is required and not found in session.');
    }

    $userId = (int) $_SESSION['user_id'];
    error_log("User ID from session: $userId");

    $gradeLevel = isset($_GET['grade_level']) ? (int) $_GET['grade_level'] : null;
    $semester   = isset($_GET['semester']) ? (int) $_GET['semester'] : null;
    $quarter    = isset($_GET['quarter']) ? (int) $_GET['quarter'] : null;

    if (!$gradeLevel || !$semester || !$quarter) {
        throw new Exception('Grade level, semester, and quarter are required');
    }

    if (!in_array($gradeLevel, [11, 12])) {
        throw new Exception('Invalid grade level. Allowed values are 11 or 12.');
    }

    if (!in_array($semester, [1, 2])) {
        throw new Exception('Invalid semester. Allowed values are 1 or 2.');
    }

    if ($quarter < 1 || $quarter > 4) {
        throw new Exception('Invalid quarter. Allowed values are 1, 2, 3, or 4.');
    }

    error_log("Received parameters: Grade Level: $gradeLevel, Semester: $semester, Quarter: $quarter");

    global $pdo;

    try {
        $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
        error_log("PDO connection successful");
    } catch (Exception $e) {
        error_log("PDO connection failed: " . $e->getMessage());
    }

    // Use direct variable embedding for debugging
    $sql = "
        SELECT 
            g.grade_id,
            s.subject_name,
            g.grade,
            g.school_year
        FROM 
            grades g
        JOIN 
            subjects s ON g.subject_id = s.subject_id
        WHERE 
            g.user_id = $userId AND
            g.grade_level = $gradeLevel AND
            g.semester = $semester AND
            g.quarter = $quarter
        ORDER BY 
            s.subject_name ASC
    ";

    //error_log("RAW SQL: $sql");

    $stmt = $pdo->query($sql);
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //error_log("Grades found: " . json_encode($grades));

    if ($grades) {
        $response['success'] = true;
        $response['grades'] = $grades;
        $response['school_year'] = $grades[0]['school_year'];
    } else {
        $response['message'] = '<div class="no-grades text-center py-2 small text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No grades found.
                                </div>
                                ';
    }

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>
