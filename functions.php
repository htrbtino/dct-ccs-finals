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

    // Prepare SQL statement to insert a new subject
    $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $subject_code, $subject_name); // Bind parameters

    // Execute the statement and check for success
    return $stmt->execute(); // Returns true on success, false on failure
}

// Function to fetch all subjects from the database
function fetchSubjects() {
    global $conn; // Use the global connection variable

    // Prepare SQL statement to select all subjects
    $sql = "SELECT * FROM subjects";
    $result = $conn->query($sql); // Execute query

    // Check if there are results and return them as an associative array
    return ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : []; // Return empty array if no subjects found
}// Function to fetch a subject by its code
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
?>