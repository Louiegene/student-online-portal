<?php
session_start();
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid student ID']);
    exit;
}

$studentId = $_GET['id'];

try {
    error_log("Fetching student details for ID: " . $studentId); // Debug log

    $stmt = $pdo->prepare("
        SELECT 
            si.student_id,
            si.user_id,
            si.lrn,
            si.first_name,
            si.middle_name,
            si.last_name,
            si.gender,
            si.birthdate,
            si.enrollment_date,
            si.grade_level,
            si.section,
            si.specific_strand,
            si.enrollment_status,
            t.trackname,
            s.strandname,
            u.email,
            u.username,
            u.profile_picture
        FROM student_info si
        LEFT JOIN Track t ON si.track_id = t.track_id
        LEFT JOIN Strand s ON si.strand_id = s.strand_id
        LEFT JOIN Users u ON si.user_id = u.user_id
        WHERE si.student_id = :id
    ");

    $stmt->execute([':id' => $studentId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        echo json_encode(['status' => 'success', 'student' => $student]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Student not found']);
    }

} catch (PDOException $e) {
    error_log("Fetch error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
