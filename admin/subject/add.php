<?php
session_start();
require '../../functions.php'; // Include database connection setup
require '../partials/header.php'; // Include header
require '../partials/side-bar.php'; // Include sidebar

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit();
}

// Initialize variables for success/error messages
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_code = postData("item_name"); // Assuming item_name is used for subject_code
    $subject_name = postData("item_description"); // Assuming item_description is used for subject_name

    // Add the new subject to the database
    if (addSubject($subject_code, $subject_name)) {
        $message = "Subject added successfully!";
    } else {
        $message = "Failed to add subject. Please try again.";
    }
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Add New Subject</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <!-- Display success/error message -->
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Add New Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="add.php"> <!-- Form submits to itself -->
            <div class="mb-3">
                <label for="item_name" class="form-label"></label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Subject Code" required>
            </div>
            <div class="mb-3">
                <label for="item_description" class="form-label"></label>
                <input type="text" class="form-control" id="item_description" name="item_description" placeholder="Subject Name" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Subject</button>
        </form>
    </div>

    <!-- Subject List Table -->
    <div class="card p-4">
        <h3 class="card-title text-left">Subject List</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $allSubjects = fetchSubjects(); // Fetch all subjects from the database
            if (!empty($allSubjects)):
                foreach ($allSubjects as $subjectDetails):
            ?>
                <tr>
                    <td><?= htmlspecialchars($subjectDetails['subject_code']) ?></td>
                    <td><?= htmlspecialchars($subjectDetails['subject_name']) ?></td>
                    <td>
                        <!-- Edit Option -->
                        <a href="edit.php?subject_code=<?= urlencode($subjectDetails['subject_code']) ?>" class="btn btn-info btn-sm">Edit</a>

                        <!-- Remove Option -->
                        <a href="delete.php?subject_code=<?= urlencode($subjectDetails['subject_code']) ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" class="text-center">No subjects found.</td> <!-- Message when no subjects are found -->
            </tr>
        <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<?php
include '../partials/footer.php'; // Include the footer
?>