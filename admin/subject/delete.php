<?php
ob_start(); // Start output buffering
session_start();
require '../../functions.php'; // Include database connection setup
require '../partials/header.php'; // Include header
require '../partials/side-bar.php'; // Include sidebar

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize variables for success/error messages
$message = "";

// Check if subject_code is provided in the URL
if (isset($_GET['subject_code'])) {
    $subject_code = $_GET['subject_code'];

    // Fetch the subject details from the database
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subject_data = $result->fetch_assoc();
    } else {
        // Handle case where subject does not exist
        header("Location: add.php");
        exit();
    }

    // Handle form submission for deleting the subject
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $delete_stmt = $conn->prepare("DELETE FROM subjects WHERE subject_code = ?");
        $delete_stmt->bind_param("s", $subject_code);

        if ($delete_stmt->execute()) {
            echo "Subject deleted successfully! Redirecting to add.php..."; // Debug message
            header("Location: add.php");
            exit();
        } else {
            $message = "Failed to delete subject. Please try again.";
        }
    }
} else {
    $message = "No subject code provided. Please go back and select a subject to delete.";
}
?>

<div class="col-md-9 col-lg-10">

<h3 class="text-left mb-5 mt-5">Delete Subject</h3>

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
        <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
    </ol>
</nav>

<div class="border p-5">
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php else: ?>
        <p class="text-left">Are you sure you want to delete the following subject record?</p>
        <ul class="text-left">
            <li><strong>Subject Code:</strong> <?= htmlspecialchars($subject_data['subject_code']) ?></li>
            <li><strong>Subject Name:</strong> <?= htmlspecialchars($subject_data['subject_name']) ?></li>
        </ul>

        <!-- Confirmation Form -->
        <form method="POST" class="text-left">
            <a href="add.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-danger">Delete Subject Record</button>
        </form>
    <?php endif; ?>
</div>

</div>

<?php
include '../partials/footer.php'; // Include the footer
ob_end_flush(); // Flush the output buffer
?>
