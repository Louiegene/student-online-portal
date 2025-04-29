<?php
session_start();
require __DIR__ . '/../../config.php'; // Include the config file

// Total Enrolled Students
$totalQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled'");
$total = $totalQuery->fetch(PDO::FETCH_ASSOC)['total'];

// Total Grade 11
$g11Query = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND grade_level = 11");
$grade11 = $g11Query->fetch(PDO::FETCH_ASSOC)['total'];

 //Total Grade 12
$g12Query = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND grade_level = 12");
$grade12 = $g12Query->fetch(PDO::FETCH_ASSOC)['total'];

//Total Female
$maleQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND gender = 'Male'");
$male = $maleQuery->fetch(PDO::FETCH_ASSOC)['total'];

//Total Female
$femaleQuery = $pdo->query("SELECT COUNT(*) AS total FROM student_info WHERE enrollment_status = 'Enrolled' AND gender = 'Female'");
$female = $femaleQuery->fetch(PDO::FETCH_ASSOC)['total'];

?>