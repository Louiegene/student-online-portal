<?php
session_start();
require dirname(dirname(__DIR__)) . '/config.php';

$user_id = $_SESSION['user_id'] ?? null;

try {
    // Check if user is logged in
    if ($user_id) {
        $result = [];

        // Fetch student info (first name, last name, LRN, track name, strand name)
        $studentStmt = $pdo->prepare("
            SELECT
                si.first_name,
                si.last_name,
                si.lrn,
                t.track_name,
                s.strand_name
            FROM
                student_info si
            LEFT JOIN Track t ON si.track_id = t.track_id
            LEFT JOIN Strand s ON si.strand_id = s.strand_id
            WHERE
                si.user_id = :user_id
        ");
        $studentStmt->execute(['user_id' => $user_id]);
        $studentInfo = $studentStmt->fetch(PDO::FETCH_ASSOC);

        if ($studentInfo) {
            $result['student_info'] = $studentInfo;
        } else {
            // If no student info found, return an error message
            echo json_encode([
                'success' => false,
                'message' => 'Student not found'
            ]);
            exit;
        }

        // Optionally fetch grades if needed (for example, based on the query params you receive)
        $grade_level = $_GET['grade_level'] ?? null;
        $semester = $_GET['semester'] ?? null;
        $quarter = $_GET['quarter'] ?? null;

        if ($grade_level && $semester && $quarter) {
            $gradesStmt = $pdo->prepare("
                SELECT
                    s.subject_name,
                    g.grade,
                    g.semester,
                    g.grade_level,
                    g.quarter
                FROM
                    grades g
                INNER JOIN
                    subjects s ON g.subject_id = s.subject_id
                WHERE
                    g.user_id = :user_id AND
                    g.grade_level = :grade_level AND
                    g.semester = :semester AND
                    g.quarter = :quarter
                ORDER BY
                    s.subject_name
            ");
            $gradesStmt->execute([
                'user_id' => $user_id,
                'grade_level' => $grade_level,
                'semester' => $semester,
                'quarter' => $quarter
            ]);
            $grades = $gradesStmt->fetchAll(PDO::FETCH_ASSOC);
            $result['grades'] = $grades ?: [];
        } else {
            // Fetch all grades if no specific query parameters are provided
            $gradesStmt = $pdo->prepare("
                SELECT
                    s.subject_name,
                    g.grade,
                    g.semester,
                    g.grade_level,
                    g.quarter
                FROM
                    grades g
                INNER JOIN
                    subjects s ON g.subject_id = s.subject_id
                WHERE
                    g.user_id = :user_id
                ORDER BY
                    g.grade_level, g.semester, g.quarter, s.subject_name
            ");
            $gradesStmt->execute(['user_id' => $user_id]);
            $grades = $gradesStmt->fetchAll(PDO::FETCH_ASSOC);
            $result['grades'] = $grades ?: [];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'You are not logged in. Please log in to view student records.'
        ]);
    }
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());

    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred. Please try again later.'
    ]);
}
?>
