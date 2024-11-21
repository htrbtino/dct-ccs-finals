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
    
    // Fetch the subject details from the database
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the subject exists
    if ($result->num_rows > 0) {
        $subject = $result->fetch_assoc();
    } else {
        // Handle case where subject does not exist
        header("Location: add.php"); // Redirect to add page if subject not found
        exit();
    }
} else {
    // Redirect to add page if no subject_code is provided
    header("Location: add.php");
    exit();
}

// Handle form submission for updating the subject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_subject_code = postData("item_name"); // New subject code
    $new_subject_name = postData("item_description"); // New subject name

    // Update the subject in the database
    $update_stmt = $conn->prepare("UPDATE subjects SET subject_code = ?, subject_name = ? WHERE subject_code = ?");
    $update_stmt->bind_param("sss", $new_subject_code, $new_subject_name, $subject_code);

    if ($update_stmt->execute()) {
        $message = "Subject updated successfully!";
        // Optionally redirect or update the displayed subject code after editing
        $subject_code = $new_subject_code; // Update local variable for display purposes
    } else {
        $message = "Failed to update subject. Please try again.";
    }
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Edit Subject</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="add.php">Add Subject</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Subject</li>
        </ol>
    </nav>

    <!-- Display success/error message -->
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Edit Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="edit.php?subject_code=<?= urlencode($subject_code) ?>"> <!-- Form submits to itself -->
            <div class="mb-3">
                <label for="item_name" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?= htmlspecialchars($subject['subject_code']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="item_description" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="item_description" name="item_description" value="<?= htmlspecialchars($subject['subject_name']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Update Subject</button>
        </form>
    </div>

</main>

<?php
include '../partials/footer.php'; // Include the footer
?>