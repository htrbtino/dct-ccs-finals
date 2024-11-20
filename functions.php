<?php    
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$db_host = 'localhost'; // Hostname
$db_user = 'root'; // Your actual MySQL username
$db_pass = ''; // Your actual database password
$db_name = 'dct-ccs-finals'; // Database name

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Uncomment this line for debugging purposes; remove it in production
// echo "Connected successfully";
?>