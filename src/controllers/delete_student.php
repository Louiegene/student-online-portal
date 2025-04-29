<?php
require dirname(dirname(__DIR__)) . '/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (empty($id) || !is_numeric($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid student ID.']);
        exit;
    }

    try {
        // Start a transaction to ensure both deletes succeed or fail together
        $pdo->beginTransaction();

        //Fetch the user_id related to the student_id
        $stmt = $pdo->prepare("SELECT user_id FROM student_info WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            echo json_encode(['status' => 'error', 'message' => 'Student not found.']);
            exit;
        }

        $user_id = $student['user_id']; // The user_id related to the student

        //Delete the user from the Users table
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        //Delete the student from the student_info table
        $stmt = $pdo->prepare("DELETE FROM student_info WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Commit the transaction if both deletions succeed
        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Student and associated user deleted successfully.']);

    } catch (PDOException $e) {
        // Rollback the transaction if there was an error
        $pdo->rollBack();
        error_log("PDO Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error occurred.']);
    }
}
?>
