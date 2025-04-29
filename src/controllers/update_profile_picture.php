<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require dirname(dirname(__DIR__)) . '/config.php';

// Check if a file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validate for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'File upload error.']);
        exit;
    }

    // Validate file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size must be less than 5MB.']);
        exit;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, and PNG files are allowed.']);
        exit;
    }

    // Determine the file extension
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'profile_' . uniqid() . '.' . $extension;

    // Upload path
    $uploadDir = dirname(__DIR__, 2) . '/public/assets/images/uploads/profile_pictures/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filePath = $uploadDir . $fileName;

    // Move the file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        // Save relative path to DB
        $relativePath = '/public/assets/images/uploads/profile_pictures/' . $fileName;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'User ID not found in session.']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE Users SET profile_picture = ? WHERE user_id = ?");
        if ($stmt->execute([$fileName, $userId])) {
            echo json_encode(['success' => true, 'newImagePath' => $relativePath]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update database.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image data received.']);
}
?>
