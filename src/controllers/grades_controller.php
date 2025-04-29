<?php
/**
 * grades_controller.php - Handles all grade-related data operations
 */
class GradesController {
    private $pdo;
    
    /**
     * Constructor - Initialize database connection
     * 
     * @param string $host Database host
     * @param string $port Database port
     * @param string $dbname Database name
     * @param string $username Database username
     * @param string $password Database password
     */
    public function __construct($host, $port, $dbname, $username, $password) {
        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            // Log the error but don't expose details
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
    }
    
    /**
     * Get student grades based on filters
     * 
     * @param int $userId Student user ID
     * @param string $quarter Selected quarter (1st, 2nd, 3rd, 4th)
     * @param string $gradeLevel Selected grade level (Grade 11, Grade 12)
     * @param string $semester Selected semester (1st, 2nd)
     * @return array Array of grade records
     */
    public function getStudentGrades($userId, $quarter, $gradeLevel, $semester) {
        if (!$userId) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    s.subject_name, 
                    g.grade, 
                    g.semester, 
                    g.quarter, 
                    g.grade_level
                FROM 
                    grades g
                INNER JOIN 
                    subjects s ON g.subject_id = s.subject_id
                WHERE 
                    g.user_id = :user_id 
                    AND g.quarter = :quarter
                    AND g.grade_level = :grade_level
                    AND g.semester = :semester
                ORDER BY 
                    s.subject_name ASC
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'quarter' => $quarter,
                'grade_level' => $gradeLevel,
                'semester' => $semester
            ]);
            
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            // Log the error for administrators
            error_log("Error fetching grades: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get all available school years for a student
     * 
     * @param int $userId Student user ID
     * @return array List of school years
     */
    public function getStudentSchoolYears($userId) {
        if (!$userId) {
            return [];
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT school_year 
                FROM grades 
                WHERE user_id = :user_id 
                ORDER BY school_year DESC
            ");
            
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (PDOException $e) {
            error_log("Error fetching school years: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calculate GPA for a student based on filters
     * 
     * @param int $userId Student user ID
     * @param string $gradeLevel Selected grade level (Grade 11, Grade 12)
     * @param string $semester Selected semester (1st, 2nd)
     * @return float|null GPA or null if no grades available
     */
    public function calculateGPA($userId, $gradeLevel, $semester) {
        if (!$userId) {
            return null;
        }
        
        try {
            $stmt = $this->pdo->prepare("
                SELECT AVG(grade) as average_grade
                FROM grades
                WHERE 
                    user_id = :user_id 
                    AND grade_level = :grade_level
                    AND semester = :semester
            ");
            
            $stmt->execute([
                'user_id' => $userId,
                'grade_level' => $gradeLevel,
                'semester' => $semester
            ]);
            
            $result = $stmt->fetch();
            return $result['average_grade'] ? round($result['average_grade'], 2) : null;
            
        } catch (PDOException $e) {
            error_log("Error calculating GPA: " . $e->getMessage());
            return null;
        }
    }
}