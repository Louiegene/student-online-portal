<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // Database connection details
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('DB_NAME') ?: 'student_portal';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: 3306;

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // Get POST data and sanitize input
        $subject_code = trim($_POST['subject_code'] ?? '');
        $subject_name = trim($_POST['subject_name'] ?? '');
        $subject_type = trim($_POST['subject_type'] ?? '');

        // Validate inputs
        if (empty($subject_code) || empty($subject_name) || empty($subject_type)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit;
        }

        // Check if subject already exists
        $pdo->beginTransaction();

        $checkQuery = "SELECT COUNT(*) FROM subjects WHERE subject_code = :subject_code";
        $checkStmt = $pdo->prepare($checkQuery);
        $checkStmt->execute([':subject_code' => $subject_code]);
        $existingCount = $checkStmt->fetchColumn();

        if ($existingCount > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Subject already exists.']);
            exit;
        }

        // Insert new subject
        $insertQuery = "INSERT INTO subjects (subject_code, subject_name, subject_type) VALUES (:subject_code, :subject_name, :subject_type)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            ':subject_code' => $subject_code,
            ':subject_name' => $subject_name,
            ':subject_type' => $subject_type,
        ]);

        // Commit transaction
        $pdo->commit();

        echo json_encode(['status' => 'success', 'message' => 'Subject added successfully!']);
    } catch (PDOException $e) {
        // Rollback transaction if an error occurs
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage(), 3, 'app.log');
        echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);
    }
}
?>
