<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and include necessary files
session_start();
require '../../functions.php'; // Include the functions file
require '../partials/header.php'; // Include the header file
require '../partials/side-bar.php'; // Include the sidebar file

// Authentication guard
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Redirect to login page if not authenticated
    exit();
}

// Initialize variables
$error = "";
$message = "";
$student = null;

// Check if student ID is provided in the query string
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];
    $student = fetchStudentById($student_id); // Fetch student details using function
    if (!$student) {
        $error = "Student not found.";
    }
} else {
    $error = "No Student ID provided.";
}

// Handle deletion confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? "";

    if (!empty($student_id)) {
        $result = deleteStudentById($student_id); // Function to delete the student from the database

        if ($result) {
            header("Location: delete.php?delete_success=1"); // Redirect back to delete.php with success
            exit();
        } else {
            $error = "Failed to delete the student record. Please try again.";
        }
    } else {
        $error = "Invalid request.";
    }
}

// Check if redirected after successful deletion
if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
    $message = "Student record deleted successfully!";
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Delete a Student</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delete Student</li>
        </ol>
    </nav>

    <!-- Success Message -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Confirmation Form -->
    <?php if ($student && !$error): ?>
        <div class="card p-4 mb-5">
            <h4 class="mb-3">Are you sure you want to delete the following student record?</h4>
            <ul>
                <li><strong>Student ID:</strong> <?php echo htmlspecialchars($student['student_id']); ?></li>
                <li><strong>First Name:</strong> <?php echo htmlspecialchars($student['first_name']); ?></li>
                <li><strong>Last Name:</strong> <?php echo htmlspecialchars($student['last_name']); ?></li>
            </ul>
            <form method="POST" action="delete.php" class="d-flex gap-2">
                <a href="register.php" class="btn btn-secondary">Cancel</a>
                <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student['student_id']); ?>">
                <button type="submit" class="btn btn-primary">Delete Student Record</button>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php
include '../partials/footer.php'; // Include the footer file
?>
