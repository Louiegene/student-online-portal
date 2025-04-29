<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';

// Assuming user_id is stored in the session
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT profile_picture FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($user && !empty($user['profile_picture'])) {
    // If profile picture exists, return its path
    echo json_encode(['profile_picture' => $user['profile_picture']]);
} else {
    // Return the default image if no profile picture
    echo json_encode(['profile_picture' => 'user_profile.png']);
}
?>
