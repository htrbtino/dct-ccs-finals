<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check authentication
session_start();
require '../../functions.php'; // Include the functions file
require '../partials/header.php'; // Include the header file
require '../partials/side-bar.php'; // Include the sidebar file

// Authentication guard
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Redirect to login page if not authenticated
    exit();
}

// Initialize variables for error/success messages
$message = "";
$error = [];

// Handle form submission for registering a student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = isset($_POST["student_id"]) ? trim($_POST["student_id"]) : "";
    $first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
    $last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";

    // Validation
    if (empty($student_id)) {
        $error[] = "Student ID is required.";
    }
    if (empty($first_name)) {
        $error[] = "First Name is required.";
    }
    if (empty($last_name)) {
        $error[] = "Last Name is required.";
    }

    // If no validation errors, proceed to register student
    if (empty($error)) {
        $result = addStudent($student_id, $first_name, $last_name, null); // Null for subject_code

        if ($result === "success") {
            header("Location: register.php?success=1"); // Redirect to register.php with success parameter
            exit();
        } elseif ($result === "duplicate") {
            $error[] = "Duplicate Student Record."; // Handle duplicate student error
        } else {
            $error[] = "Failed to register student. Please try again.";
        }
    }
}

// Check if redirected after successful registration or deletion
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Student registered successfully!";
}
if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
    $message = "Student deleted successfully!";
}

// Fetch existing students for display
$students = fetchStudents();
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Register a New Student</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>

    <!-- Display success message -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display errors -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>System Errors:</strong>
            <ul>
                <?php foreach ($error as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Student Registration Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="student_id" class="form-label"></label>
                <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Student ID">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label"></label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label"></label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Student</button>
        </form>
    </div>

    <!-- Student List Table -->
    <div class="card p-4">
        <h3 class="card-title">Student List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                        <td>
                            <a href="edit.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-info btn-sm">Edit</a>
                            <a href="delete.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                            <a href="attach_subject.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-warning btn-sm">Attach Subject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No students registered yet.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include '../partials/footer.php'; // Include the footer file
?>
