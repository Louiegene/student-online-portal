<?php
session_start();
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

// Check admin session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized. Please log in as admin.']);
    exit;
}

try {
    // DB connection
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Collect input values
    $subject_id = $_POST['subject_id'] ?? '';
    $subject_code = trim($_POST['subject_code'] ?? '');
    $subject_name = trim($_POST['subject_name'] ?? '');
    $subject_type = trim($_POST['subject_type'] ?? '');

    // Input validation
    if (empty($subject_id) || empty($subject_code) || empty($subject_name) || empty($subject_type)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }

    // Check existence
    $check = $pdo->prepare("SELECT COUNT(*) FROM subjects WHERE subject_id = :subject_id");
    $check->execute(['subject_id' => $subject_id]);

    if ($check->fetchColumn() == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Subject not found.']);
        exit;
    }

    // Update
    $sql = "
        UPDATE subjects
        SET subject_code = :subject_code,
            subject_name = :subject_name,
            subject_type = :subject_type
        WHERE subject_id = :subject_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'subject_code' => $subject_code,
        'subject_name' => $subject_name,
        'subject_type' => $subject_type,
        'subject_id' => $subject_id
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Subject updated successfully.',
            'updated_subject' => [
                'subject_id' => $subject_id,
                'subject_code' => $subject_code,
                'subject_name' => $subject_name,
                'subject_type' => $subject_type
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes were made.']);
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error updating subject. Please try again later.']);
}
?>
