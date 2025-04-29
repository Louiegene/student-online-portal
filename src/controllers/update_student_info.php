<?php
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate track_id and strand_id (if required)
        $validTracks = [1, 2]; // Add valid track IDs here
        $validStrands = [1, 2, 3, 4, 5, 6, 7, 8]; // Add valid strand IDs here

        if (!in_array($_POST['track_id'], $validTracks)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid track ID.']);
            exit;
        }

        if (!in_array($_POST['strand_id'], $validStrands)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid strand ID.']);
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE student_info SET 
                lrn = :lrn,
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                enrollment_status = :enrollment_status,
                track_id = :track_id,
                strand_id = :strand_id,
                specific_strand = :specific_strand
            WHERE student_id = :student_id
        ");

        $stmt->execute([
            ':lrn' => $_POST['lrn'],
            ':first_name' => $_POST['first_name'],
            ':middle_name' => $_POST['middle_name'],
            ':last_name' => $_POST['last_name'],
            ':enrollment_status' => $_POST['enrollment_status'],
            ':track_id' => $_POST['track_id'],
            ':strand_id' => $_POST['strand_id'],
            ':specific_strand' => $_POST['specific_strand'],
            ':student_id' => $_POST['student_id'],
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Student updated successfully.']);
        
    } catch (PDOException $e) {
        error_log("Update error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
