<?php
session_start();
require '../partials/header.php'; // Updated path to header.php
require '../partials/side-bar.php'; // Updated path to side-bar.php

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not authenticated
    exit();
}
?>

<!-- Content Area -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
    <h1 class="h2">Add a New Subject</h1>

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Subject</li>
        </ol>
    </nav>

    <!-- Add New Item Form -->
    <div class="card p-4 mb-5">
        <form method="getPostDataT" action="add.php"> <!-- Form submits to itself -->
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

    <!-- Placeholder for Success/Error Messages -->
    <div id="message-area">
        <!-- Success or error messages will go here later -->
    </div>

</main>

<?php
include '../partials/footer.php'; // Include the footer
?>