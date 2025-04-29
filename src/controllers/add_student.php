<?php
session_start();

header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    jsonResponse('error', 'Invalid request method.');
}

// Response helper
function jsonResponse($status, $message, $extra = []) {
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit; // Ensure the script stops after responding.
}

// DB Connection
$host = getenv('DB_HOST') ?: '127.0.0.1';
$dbname = getenv('DB_NAME') ?: 'student_portal';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: 3306;
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Sanitize and validate inputs
    $school_year      = htmlspecialchars(trim($_POST['school_year']));
    $username         = htmlspecialchars(trim($_POST['username']));
    $email            = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $birthdate        = htmlspecialchars(trim($_POST['birthdate']));
    $enrollment_date  = htmlspecialchars(trim($_POST['enrollment_date']));
    $lrn              = filter_var(trim($_POST['lrn']), FILTER_VALIDATE_INT);
    $first_name       = htmlspecialchars(trim($_POST['first_name']));
    $middle_name      = htmlspecialchars(trim($_POST['middle_name'])) ?: null;
    $last_name        = htmlspecialchars(trim($_POST['last_name']));
    $gender           = htmlspecialchars(trim($_POST['gender']));
    $grade_level      = filter_var(trim($_POST['grade_level']), FILTER_VALIDATE_INT);
    $section          = htmlspecialchars(trim($_POST['section']));
    $track            = filter_var(trim($_POST['track']), FILTER_VALIDATE_INT);
    $strand           = filter_var(trim($_POST['strand']), FILTER_VALIDATE_INT);
    $specific_strand  = htmlspecialchars(trim($_POST['specific_strand'])) ?: null;

    // Check required fields
    $missingFields = [];
    foreach ([
        'school_year' => $school_year, 'birthdate' => $birthdate, 'enrollment_date' => $enrollment_date,
        'lrn' => $lrn, 'first_name' => $first_name, 'last_name' => $last_name,
        'gender' => $gender, 'grade_level' => $grade_level, 'section' => $section,
        'track' => $track, 'strand' => $strand
    ] as $field => $value) {
        if (empty($value)) $missingFields[] = $field;
    }

    if ($missingFields) {
        jsonResponse('error', 'Please fill in all required fields.', ['missingFields' => $missingFields]);
    }

    if (!$email) {
        jsonResponse('error', 'Invalid email format.');
    }

    // Check for duplicate username or email (ensure this is done before further processing)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE username = :username OR email = :email");
    $stmt->execute([':username' => $username, ':email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        jsonResponse('error', 'Username or email already exists.');
    }

    // Begin transaction
    $pdo->beginTransaction();

    // Create default password from birthdate MMDDYYYY
    $birthdateParts = explode('-', $birthdate);
    if (count($birthdateParts) !== 3 || !checkdate($birthdateParts[1], $birthdateParts[2], $birthdateParts[0])) {
        jsonResponse('error', 'Invalid birthdate format.');
    }
    $defaultPassword = $birthdateParts[1] . $birthdateParts[2] . $birthdateParts[0];
    $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

    // Insert into Users
    $userStmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (:username, :email, :password)");
    $userStmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword,
    ]);
    $user_id = $pdo->lastInsertId();

    // Insert into student_info
    $studentStmt = $pdo->prepare("
        INSERT INTO student_info 
        (user_id, enrollment_date, school_year, lrn, first_name, middle_name, last_name, gender, grade_level, section, birthdate, track_id, strand_id, specific_strand) 
        VALUES 
        (:user_id, :enrollment_date, :school_year, :lrn, :first_name, :middle_name, :last_name, :gender, :grade_level, :section, :birthdate, :track_id, :strand_id, :specific_strand)
    ");
    $studentStmt->execute([
        ':user_id'         => $user_id,
        ':enrollment_date' => $enrollment_date,
        ':school_year'     => $school_year,
        ':lrn'             => $lrn,
        ':first_name'      => $first_name,
        ':middle_name'     => $middle_name,
        ':last_name'       => $last_name,
        ':gender'          => $gender,
        ':grade_level'     => $grade_level,
        ':section'         => $section,
        ':birthdate'       => $birthdate,
        ':track_id'        => $track,
        ':strand_id'       => $strand,
        ':specific_strand' => $specific_strand
    ]);

    $pdo->commit();
    jsonResponse('success', 'Student added successfully!');
    
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();

    if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
        jsonResponse('error', 'Duplicate entry. The username or email already exists.');
    } else {
        jsonResponse('error', 'A database error occurred. Please try again.');
    }
}
?>
