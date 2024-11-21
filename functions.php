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
?>