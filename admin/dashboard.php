<?php
session_start();
require_once '../functions.php'; // Adjust path based on your directory structure

require './partials/header.php';
require './partials/side-bar.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit();
}

// Fetch counts from the database

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

// Initialize variables for failed and passed students
$failed_students = 0; // Initialize to 0 if not using
$total_passed = 0;   // Initialize to 0 if not using

// Since we're removing the status, we will not count passed or failed students.
// If you need other statistics, you can add them here.

?>

<!-- Template Files here -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Dashboard</h1>        
    
    <div class="row mt-5">
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                <div class="card-body text-primary">
                    <h5 class="card-title"><?php echo $total_subjects; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title"><?php echo $total_students; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-danger mb-3">
                <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                <div class="card-body text-danger">
                    <h5 class="card-title"><?php echo $failed_students; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-3">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                <div class="card-body text-success">
                    <h5 class="card-title"><?php echo $total_passed; ?></h5> <!-- Fixed closing tag -->
                </div>
            </div>
        </div>
    </div>    
</main>
<!-- Template Files here -->