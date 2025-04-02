<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit;
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>
<a href="../../src/controllers/logout.php">Logout</a>