<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$db_host = 'localhost'; 
$db_user = 'root'; 
$db_pass = ''; 
$db_name = 'dct-ccs-finals';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to handle user login
function login($email, $password) {
    global $conn; // Use the global connection variable
    
    // Hash the password
    $hashed_password = md5($password);
    
    // Prepare and execute SQL query to check credentials
    $sql = "SELECT * FROM users WHERE email=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    
    // Return the result set
    return $stmt->get_result();
}

// Function to retrieve POST data safely
function postData($key) {
    return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key])) : null;
}

// Function to add a new subject
function addSubject($subject_code, $subject_name) {
    global $conn; // Use the global connection variable

    // Check if the subject already exists
    $check_sql = "SELECT * FROM subjects WHERE subject_code = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $subject_code);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Subject already exists
        return "duplicate";
    }

    // Prepare SQL statement to insert a new subject
    $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $subject_code, $subject_name);

    // Execute the statement and check for success
    return $stmt->execute() ? "success" : "error";
}

// Function to fetch all subjects from the database
function fetchSubjects() {
    global $conn;

    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql);

    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Function to fetch all students
function fetchStudents() {
    global $conn;

    $sql = "SELECT students.student_id, students.first_name, students.last_name, subjects.subject_code 
            FROM students 
            LEFT JOIN students_subjects ON students.id = students_subjects.student_id 
            LEFT JOIN subjects ON students_subjects.subject_id = subjects.id";

    $result = $conn->query($sql);

    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Function to add a new student
function addStudent($student_id, $first_name, $last_name, $subject_code) {
    global $conn;

    // Check if the student already exists
    $check_sql = "SELECT * FROM students WHERE student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        return "duplicate"; // Student already exists
    }

    // Check if the subject code is valid
    $subject_sql = "SELECT id FROM subjects WHERE subject_code = ?";
    $subject_stmt = $conn->prepare($subject_sql);
    $subject_stmt->bind_param("s", $subject_code);
    $subject_stmt->execute();
    $subject_result = $subject_stmt->get_result();

    if ($subject_result->num_rows === 0) {
        return "invalid_subject"; // Invalid subject code
    }

    $subject_id = $subject_result->fetch_assoc()['id'];

    // Add the student to the students table
    $insert_student_sql = "INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)";
    $student_stmt = $conn->prepare($insert_student_sql);
    $student_stmt->bind_param("sss", $student_id, $first_name, $last_name);

    if (!$student_stmt->execute()) {
        return "error"; // Failed to add student
    }

    $new_student_id = $conn->insert_id; // Get the newly inserted student's ID

    // Add the student-subject relationship to the students_subjects table
    $insert_subject_sql = "INSERT INTO students_subjects (student_id, subject_id, grade) VALUES (?, ?, 0.00)";
    $subject_stmt = $conn->prepare($insert_subject_sql);
    $subject_stmt->bind_param("ii", $new_student_id, $subject_id);

    return $subject_stmt->execute() ? "success" : "error";
}

// Function to fetch a subject by its code
function fetchSubjectByCode($subject_code) {
    global $conn;

    $sql = "SELECT * FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_assoc();
}

// Function to update an existing subject
function updateSubject($old_subject_code, $new_subject_code, $new_subject_name) {
    global $conn;

    $sql = "UPDATE subjects SET subject_code = ?, subject_name = ? WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_subject_code, $new_subject_name, $old_subject_code);

    return $stmt->execute();
}

// Function to delete a subject by its code
function deleteSubject($subject_code) {
    global $conn;

    $sql = "DELETE FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_code);

    return $stmt->execute();
}
?>
