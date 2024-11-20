<?php    
    session_start(); // Start the session at the beginning of the script
    
    // config.php
    define("DB_HOST", "localhost");
    define("DB_USER", "your_db_username"); // replace with your database username
    define("DB_PASSWORD", "your_db_password"); // replace with your database password
    define("DB_NAME", "your_database_name"); // replace with your database name
    
    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
    
?>