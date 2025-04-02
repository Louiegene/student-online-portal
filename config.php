<?php
$host = "127.0.0.1";
$dbname = "student_portal";
$username = 'root';
$password = 'Polymorph@28';
$port = 3306;

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log the error message to a file for debugging purposes
    error_log("Database connection failed: " . $e->getMessage(), 3, 'error.log');
    die("Database connection failed. Please check the error log for more details.");
}
?>
