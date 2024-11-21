<?php
require_once 'functions.php'; // Include database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="partials/custom-dashboard.css"> <!-- Link to your custom CSS -->
</head>
<body>

<?php include 'partials/header.php'; ?> <!-- Include header -->
<?php include 'partials/side-bar.php'; ?> <!-- Include sidebar -->

<div class="container">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        <label for="email"><b>Email</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

        <label for="name"><b>Name</b></label>
        <input type="text" placeholder="Enter Your Name" name="name" required>

        <button type="submit">Register</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?> <!-- Include footer -->

</body>
</html>