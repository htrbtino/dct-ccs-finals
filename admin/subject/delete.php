<?php
session_start();

require '../partials/header.php'; // Include header
require '../partials/side-bar.php'; // Include sidebar

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize variables for success/error messages
$message = "";

// Check if subject_code is provided in the URL
if (isset($_GET['subject_code'])) {
    $subject_code = $_GET['subject_code'];

    // Prepare SQL statement to delete the subject
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code); // Bind parameters

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $message = "Subject deleted successfully!";
    } else {
        $message = "Failed to delete subject. Please try again.";
    }
} else {
    // Redirect to add page if no subject_code is provided
    header("Location: add.php");
    exit();
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Delete Subject</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
            <li class="breadcrumb-item active" aria-current="page">Delete Subject</li>
        </ol>
    </nav>

    <!-- Display success/error message -->
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <a href="add.php" class="btn btn-secondary">Back to Subject List</a> <!-- Link back to the subject list -->
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            No subject code provided. Please go back and select a subject to delete.
        </div>
        <a href="add.php" class="btn btn-secondary">Back to Subject List</a> <!-- Link back to the subject list -->
    <?php endif; ?>
</main>

<?php
include '../partials/footer.php'; // Include the footer
?>