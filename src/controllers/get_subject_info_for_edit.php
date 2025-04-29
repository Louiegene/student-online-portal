<?php
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Prepare SQL query to get subject details by subject_id
        $stmt = $pdo->prepare("
            SELECT 
                subject_id,
                subject_code,
                subject_name,
                subject_type
            FROM subjects
            WHERE subject_id = :id
        ");
        $stmt->execute([':id' => $id]);
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subject) {
            // If the subject is found, return the data in JSON format
            echo json_encode(['status' => 'success', 'subject' => $subject]);
        } else {
            // If no subject found
            echo json_encode(['status' => 'error', 'message' => 'Subject not found.']);
        }
    } catch (PDOException $e) {
        // Log error and return a friendly message
        error_log('Error fetching subject: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error.']);
    }
} else {
    // Return an error if the subject ID is not provided
    echo json_encode(['status' => 'error', 'message' => 'Missing subject ID.']);
}
?>
