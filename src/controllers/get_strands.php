<?php
require __DIR__ . '/../../config.php';
header('Content-Type: application/json');

$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbname = getenv('DB_NAME') ?: 'student_portal';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Get tracks
    $tracks = $pdo->query("SELECT track_id, trackname FROM Track")->fetchAll();

    // Get strands with their track_id
    $strands = $pdo->query("SELECT strand_id, strandname, track_id FROM Strand")->fetchAll();

    echo json_encode(['tracks' => $tracks, 'strands' => $strands]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
}
?>
