<?php
session_start();
require '../../config.php';  // Ensure this includes the correct DB connection setup

// Check if the connection is established
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default query to fetch all grades (You can also adjust for filtering here if needed)
$sql = "SELECT * FROM grades";

// Execute the query and handle potential errors
$result = $conn->query($sql);
if ($result === false) {
    // In case of query failure
    echo "Error: " . $conn->error;
} else {
    // Fetch rows and display them in table format
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
        echo "<td>" . htmlspecialchars($row['school_year']) . "</td>";
        echo "<td>" . htmlspecialchars($row['semester']) . "</td>";
        echo "<td>" . htmlspecialchars($row['quarter']) . "</td>";
        echo "</tr>";
    }
}

// Close the connection
$conn->close();
?>
