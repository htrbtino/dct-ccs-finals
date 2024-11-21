<?php
include '../partials/side-bar.php';


$dashboardPage = 'admin/dashboard.php';
include '../partials/side-bar.php';
?>


    <!-- Add New Item Form -->
    <div class="card p-4 mb-5">
        <form method="POST" action="add.php"> <!-- Assuming a processing script -->
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" required>
            </div>
            <div class="mb-3">
                <label for="item_description" class="form-label">Item Description</label>
                <textarea class="form-control" id="item_description" name="item_description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm w-100">Add Item</button>
        </form>
    </div>

</div>




<?php
include '../partials/footer.php';
?>
