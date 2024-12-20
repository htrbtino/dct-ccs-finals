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

// Function to check if a subject exists
function subjectExists($subject_code) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
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
    return $stmt->execute() ? "success" : "error"; // Return success, duplicate, or error
}

// Function to fetch all subjects from the database
function fetchSubjects() {
    global $conn; // Use the global connection variable

    // Prepare SQL statement to select all subjects
    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql); // Execute query

    // Check if there are results and return them as an associative array
    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : []; // Return empty array if no subjects found
}

// Function to fetch a subject by its code
function fetchSubjectByCode($subject_code) {
    global $conn; // Use the global connection variable

    // Prepare SQL statement to select a subject by its code
    $sql = "SELECT * FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_code); // Bind parameters
    $stmt->execute();
    
    // Return the result set as an associative array
    return $stmt->get_result()->fetch_assoc(); // Fetch the subject details as an associative array
}

// Function to update an existing subject
function updateSubject($old_subject_code, $new_subject_code, $new_subject_name) {
    global $conn; // Use the global connection variable

    // Prepare SQL statement to update a subject
    $sql = "UPDATE subjects SET subject_code = ?, subject_name = ? WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_subject_code, $new_subject_name, $old_subject_code); // Bind parameters

    // Execute the statement and check for success
    return $stmt->execute(); // Returns true on success, false on failure
}

// Function to delete a subject by its code
function deleteSubject($subject_code) {
    global $conn; // Use the global connection variable

    // Prepare SQL statement to delete a subject
    $sql = "DELETE FROM subjects WHERE subject_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $subject_code); // Bind parameters

    // Execute the statement and check for success
    return $stmt->execute(); // Returns true on success, false on failure
}

// Function to add a student
function addStudent($student_id, $first_name, $last_name, $subject_code) {
    global $conn;

    // Check for duplicate student ID
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return "duplicate";
    }

    // Insert student into the database
    $stmt = $conn->prepare("INSERT INTO students (student_id, first_name, last_name) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $student_id, $first_name, $last_name);
    if ($stmt->execute()) {
        // Link student to subject in the students_subjects table
        $student_id = $conn->insert_id; // Get the last inserted student ID
        $subject_stmt = $conn->prepare("INSERT INTO students_subjects (student_id, subject_id, grade) VALUES (?, (SELECT id FROM subjects WHERE subject_code = ?), 0)");
        $subject_stmt->bind_param("is", $student_id, $subject_code);
        $subject_stmt->execute();
        return "success";
    } else {
        return "error";
    }
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
// Function to fetch a student by their ID
function fetchStudentById($student_id) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Function to update a student
function updateStudent($student_id, $first_name, $last_name) {
    global $conn;

    $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ? WHERE student_id = ?");
    $stmt->bind_param("sss", $first_name, $last_name, $student_id);

    return $stmt->execute();
}
function deleteStudentById($student_id) {
    global $conn; // Use global database connection

    // Prepare SQL to delete the student record
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);

    // Execute and return the result
    return $stmt->execute();
}
?>
