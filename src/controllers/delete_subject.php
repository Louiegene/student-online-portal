<?php
require dirname(dirname(__DIR__)) . '/config.php'; // Include database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Validate the provided subject ID
    if (empty($id) || !is_numeric($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid subject ID.']);
        exit;
    }

    try {
        // Start a transaction to ensure both deletes succeed or fail together
        $pdo->beginTransaction();

        // Fetch associated data for the subject if needed (e.g., check for related records)
        $stmt = $pdo->prepare("SELECT * FROM subjects WHERE subject_id = :subject_id");
        $stmt->bindParam(':subject_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$subject) {
            echo json_encode(['status' => 'error', 'message' => 'Subject not found.']);
            exit;
        }

        // Delete the subject from the subjects table
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE subject_id = :subject_id");
        $stmt->bindParam(':subject_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction if the deletion succeeds
        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Subject deleted successfully.']);

    } catch (PDOException $e) {
        // Rollback the transaction if there was an error
        $pdo->rollBack();
        error_log("PDO Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
    }
}
?>
