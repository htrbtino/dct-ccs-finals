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
    <h1 class="h2">Add New Item</h1>

    <!-- Add New Item Form -->
    <div class="card p-4 mb-5">
        <form method="getPostDataT" action="add.php"> <!-- Form submits to itself -->
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter item name" required>
            </div>
            <div class="mb-3">
                <label for="item_description" class="form-label">Item Description</label>
                <textarea class="form-control" id="item_description" name="item_description" rows="3" placeholder="Enter item description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Item</button>
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