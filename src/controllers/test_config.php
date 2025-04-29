<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../config.php'; // Adjust the path if needed

// Check if the $pdo variable is defined (assuming it's your database connection)
if (isset($pdo)) {
    echo "Config file is accessible, and \$pdo is defined.";
} else {
    echo "Config file is not accessible, or \$pdo is not defined.";
}
?>