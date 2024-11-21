<?php
session_start();
require_once 'functions.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch counts from the database
// Assuming you have a 'subjects' table and a 'students' table with appropriate fields

// Count total subjects
$stmt = $conn->prepare("SELECT COUNT(*) as total_subjects FROM subjects");
$stmt->execute();
$subject_result = $stmt->get_result();
$total_subjects = $subject_result->fetch_assoc()['total_subjects'];

// Count total students
$stmt = $conn->prepare("SELECT COUNT(*) as total_students FROM students");
$stmt->execute();
$student_result = $stmt->get_result();
$total_students = $student_result->fetch_assoc()['total_students'];

// Count failed students (assuming there's a 'status' field that indicates pass/fail)
$stmt = $conn->prepare("SELECT COUNT(*) as total_failed FROM students WHERE status = 'failed'");
$stmt->execute();
$failed_result = $stmt->get_result();
$total_failed = $failed_result->fetch_assoc()['total_failed'];

// Count passed students
$stmt = $conn->prepare("SELECT COUNT(*) as total_passed FROM students WHERE status = 'passed'");
$stmt->execute();
$passed_result = $stmt->get_result();
$total_passed = $passed_result->fetch_assoc()['total_passed'];
?>

<!-- Template Files here -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Dashboard</h1>        
    
    <div class="row mt-5">
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                <div class="card-body text-primary">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-danger mb-3">
                <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                <div class="card-body text-danger">
                    <h5 class="card-title">0</h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title">0></h5>
                </div>
            </div>
        </div>
    </div>    
</main>
<!-- Template Files here -->