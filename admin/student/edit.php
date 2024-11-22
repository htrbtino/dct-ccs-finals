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

// Check if student_id is provided in the URL
if (!isset($_GET['student_id'])) {
    header("Location: register.php"); // Redirect to register.php if no student ID
    exit();
}

$student_id = $_GET['student_id'];

// Fetch student details
$student = fetchStudentById($student_id);
if (!$student) {
    header("Location: register.php"); // Redirect if student not found
    exit();
}

// Handle form submission for updating a student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
    $new_last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";

    // Validation
    if (empty($new_first_name)) {
        $error[] = "First Name is required";
    }
    if (empty($new_last_name)) {
        $error[] = "Last Name is required";
    }

    // If no validation errors, proceed to update the student
    if (empty($error)) {
        $result = updateStudent($student_id, $new_first_name, $new_last_name);

        if ($result) {
            $message = "Student updated successfully!";
            // Refresh student details after successful update
            $student = fetchStudentById($student_id);
        } else {
            $error[] = "Failed to update student. Please try again.";
        }
    }
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Edit Student</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
        </ol>
    </nav>

    <!-- Display errors -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>System Errors</strong>
            <ul>
                <?php foreach ($error as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Student Edit Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="edit.php?student_id=<?= urlencode($student_id) ?>">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" 
                       value="<?= htmlspecialchars($student['student_id']) ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" 
                       value="<?= htmlspecialchars($student['first_name']) ?>" 
                       placeholder="Enter First Name">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" 
                       value="<?= htmlspecialchars($student['last_name']) ?>" 
                       placeholder="Enter Last Name">
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Update Student</button>
        </form>
    </div>
</main>

<?php
include '../partials/footer.php'; // Include the footer file
?>
