<?php
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
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage(), 3, 'app.log');
    // Don't expose connection details to the user
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
        if (strpos($_SERVER['REQUEST_URI'], 'api') !== false || 
            strpos($_SERVER['REQUEST_URI'], 'controllers') !== false) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
        } else {
            echo "Database connection failed. Please try again later.";
        }
    }
    exit;
}
?>