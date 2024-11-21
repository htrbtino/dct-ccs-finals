<?php
session_start();
require '../../functions.php'; // Include database connection setup
require '../partials/header.php'; // Include header
require '../partials/side-bar.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize variables
$message = "";
$error = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = postData('student_id');
    $first_name = postData('first_name');
    $last_name = postData('last_name');
    $subject_code = postData('subject_code');

    // Validate input
    if (empty($student_id)) {
        $error[] = "Student ID is required.";
    }
    if (empty($first_name)) {
        $error[] = "First Name is required.";
    }
    if (empty($last_name)) {
        $error[] = "Last Name is required.";
    }
    if (empty($subject_code)) {
        $error[] = "Subject Code is required.";
    }

    // Add student if no errors
    if (empty($error)) {
        $result = addStudent($student_id, $first_name, $last_name, $subject_code);

        if ($result === "success") {
            $message = "Student registered successfully!";
        } elseif ($result === "duplicate") {
            $error[] = "Duplicate student record.";
        } elseif ($result === "invalid_subject") {
            $error[] = "Invalid subject code. Please check.";
        } else {
            $error[] = "Failed to register student. Please try again.";
        }
    }
}

// Fetch all students
$students = fetchStudents();
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
    <h1 class="h2">Register Student</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>

    <!-- Display Errors -->
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

    <!-- Display Success Message -->
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" 
                       placeholder="Enter Student ID" 
                       value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" 
                       placeholder="Enter First Name" 
                       value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" 
                       placeholder="Enter Last Name" 
                       value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" 
                       placeholder="Enter Subject Code" 
                       value="<?php echo isset($_POST['subject_code']) ? htmlspecialchars($_POST['subject_code']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Register Student</button>
        </form>
    </div>

    <!-- Student List Table -->
    <div class="card p-4">
        <h3 class="card-title text-left">Registered Students</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Subject Code</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td><?= htmlspecialchars($student['first_name']) ?></td>
                        <td><?= htmlspecialchars($student['last_name']) ?></td>
                        <td><?= htmlspecialchars($student['subject_code']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No students registered.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include '../partials/footer.php';
?>
