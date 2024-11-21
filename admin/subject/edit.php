</php
session_start();
require '../partials/header.php'; // 
require '../partials/side-bar.php'; // 


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
require '../partials/footer.php';
?>