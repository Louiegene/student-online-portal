<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    // Check if the email format is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['exists' => false, 'error' => 'Invalid email format', 'email' => $email]);
        exit;
    }

    // Database connection parameters
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('DB_NAME') ?: 'student_portal';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: 3306;

    try {
        // Create a PDO instance and establish a connection
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the SQL statement to check if the email exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // Check if email exists and return appropriate response
        if ($count > 0) {
            echo json_encode(['exists' => true, 'error' => 'Email already in use']);
        } else {
            echo json_encode(['exists' => false, 'error' => '']);
        }

    } catch (PDOException $e) {
        echo json_encode(['exists' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
