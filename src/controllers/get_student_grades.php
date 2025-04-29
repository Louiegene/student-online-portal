<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';

// Get and sanitize parameters
$student_id = $_GET['student_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;
$selectedQuarter = $_GET['quarter'] ?? '1st';
$selectedGradeLevel = isset($_GET['grade_level']) ? trim((string) $_GET['grade_level']) : null;

// Debug logs for development
error_log("GET data: " . print_r($_GET, true));
error_log("Received grade level: " . var_export($selectedGradeLevel, true));

// Validate grade level
$validGradeLevels = ['11', '12'];
if (!in_array($selectedGradeLevel, $validGradeLevels, true)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing grade level.'
    ]);
    exit;
}

// Validate quarter
$validQuarters = ['1st', '2nd', '3rd', '4th'];
if (!in_array($selectedQuarter, $validQuarters)) {
    $selectedQuarter = '1st';
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Check if we have the minimum required parameters
    if (!$student_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing student ID.'
        ]);
        exit;
    }

    // First fetch student details
    $studentStmt = $pdo->prepare("
        SELECT 
            si.*,
            t.trackname,
            str.strandname
        FROM 
            student_info si
        LEFT JOIN
            track t ON si.track_id = t.track_id
        LEFT JOIN
            strand str ON si.strand_id = str.strand_id
        WHERE 
            si.student_id = :student_id
    ");
    $studentStmt->execute(['student_id' => $student_id]);
    $student = $studentStmt->fetch();

    if (!$student) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Student not found.'
        ]);
        exit;
    }

    // Use user_id from the student record if not provided in the request
    if (!$user_id) {
        $user_id = $student['user_id'];
    }

    $response = [
        'status' => 'success',
        'student' => [
            'student_id' => $student['student_id'],
            'user_id' => $user_id,
            'full_name' => $student['first_name'] . ' ' . ($student['middle_name'] ? $student['middle_name'] . ' ' : '') . $student['last_name'],
            'lrn' => $student['lrn'],
            'trackname' => $student['trackname'] ?? 'N/A',
            'strand' => $student['strandname'] ?? 'N/A',
            'grade_level' => $student['grade_level'],
            'section' => $student['section'],
            'enrollment_status' => $student['enrollment_status'] ?? 'N/A'
        ]
    ];

    // Only fetch grades if we have the needed parameters
    if ($user_id) {
        // Now fetch grades - joining with student_info to filter by grade_level
        $stmt = $pdo->prepare("
            SELECT 
                g.grade_id,
                g.grade,
                s.subject_name,
                g.quarter,
                g.semester,
                g.school_year,
                si.grade_level
            FROM 
                grades g
            INNER JOIN 
                subjects s ON g.subject_id = s.subject_id
            INNER JOIN
                student_info si ON g.user_id = si.user_id
            WHERE 
                g.user_id = :user_id 
                AND g.quarter = :quarter
                AND si.grade_level = :grade_level
        ");
        
        $stmt->execute([
            'user_id' => $user_id,
            'quarter' => $selectedQuarter,
            'grade_level' => $selectedGradeLevel
        ]);
        
        $grades = $stmt->fetchAll();
        $response['grades'] = $grades;
        
        if (empty($grades)) {
            $response['message'] = 'No grades available for this student in the selected quarter and grade level.';
        }
    } else {
        $response['grades'] = [];
        $response['message'] = 'Only student information available. User ID missing for grades.';
    }

    echo json_encode($response);

} catch (PDOException $e) {
    error_log("Database error in get_student_grades.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Unable to fetch grades at this time. Please try again later.'
    ]);
}
?>