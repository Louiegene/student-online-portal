<?php
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("
            SELECT 
                s.student_id,
                s.lrn,
                s.first_name,
                s.middle_name,
                s.last_name,
                s.enrollment_status,
                s.track_id,
                s.strand_id,
                s.specific_strand,
                t.trackname,
                st.strandname
            FROM student_info s
            LEFT JOIN track t ON s.track_id = t.track_id
            LEFT JOIN strand st ON s.strand_id = st.strand_id
            WHERE s.student_id = :id
        ");
        $stmt->execute([':id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            echo json_encode(['status' => 'success', 'student' => $student]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
        }
    } catch (PDOException $e) {
        error_log('Error fetching student: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing student ID.']);
}
?>
