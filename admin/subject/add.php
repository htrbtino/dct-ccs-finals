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
$error = [];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_code = isset($_POST["item_name"]) ? trim($_POST["item_name"]) : ""; // Subject code
    $subject_name = isset($_POST["item_description"]) ? trim($_POST["item_description"]) : ""; // Subject name

    // Validate input
    if (empty($subject_code)) {
        $error[] = "Subject Code is required";
    }
    if (empty($subject_name)) {
        $error[] = "Subject Name is required";
    }

    // If no errors, proceed with adding the subject
    if (empty($error)) {
        if (addSubject($subject_code, $subject_name)) {
            $message = "Subject added successfully!";
        } else {
            $error[] = "Failed to add subject. Please try again.";
        }
    }
}
?>

<!-- Include Bootstrap CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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

    <!-- Add New Subject Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="add.php"> <!-- Form submits to itself -->
            <div class="mb-3">
                <label for="item_name" class="form-label"></label>
                <input type="text" class="form-control" id="item_name" name="item_name" 
                       placeholder="Enter Subject Code" 
                       value="<?php echo isset($_POST['item_name']) ? htmlspecialchars($_POST['item_name']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="item_description" class="form-label"></label>
                <input type="text" class="form-control" id="item_description" name="item_description" 
                       placeholder="Enter Subject Name" 
                       value="<?php echo isset($_POST['item_description']) ? htmlspecialchars($_POST['item_description']) : ''; ?>">
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
