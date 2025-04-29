<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');

    // Check if the username is empty
    if (empty($usernameInput)) {
        echo json_encode(['exists' => false, 'error' => 'Username is required']);
        exit;
    }

    // Database connection parameters
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $dbname = getenv('DB_NAME') ?: 'student_portal';
    $dbuser = getenv('DB_USERNAME') ?: 'root';
    $dbpass = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: 3306;

    try {
        // Establish PDO connection
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception mode

        // Prepare the SQL statement to check if the username exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE username = :username");
        $stmt->bindParam(':username', $usernameInput, PDO::PARAM_STR);  // Bind the parameter
        $stmt->execute();

        // Check if the username exists
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(['exists' => true, 'error' => 'Username already taken']);
        } else {
            echo json_encode(['exists' => false, 'error' => '']);
        }
    } catch (PDOException $e) {
        echo json_encode(['exists' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
