<!-- register.php -->
<?php
require_once('functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email']);
    $full_name = sanitize($_POST['full_name']);
    $password = sanitize($_POST['password']);
    $user_type = sanitize($_POST['user_type']);

    // Backend validations
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please provide a valid email address.";
    } elseif (strlen($password) < 8 || strlen($password) > 64) {
        $error_message = "Password must be between 8 and 64 characters.";
    } elseif (strlen($full_name) < 3 || strlen($full_name) > 50) {
        $error_message = "Full name must be between 3 and 50 characters.";
    } else {
        // Secure password hashing (replace md5 with password_hash in production)
        $hashedPassword = md5($password);

        $query = "INSERT INTO users (email, full_name, password, user_type) 
                  VALUES ('$email', '$full_name', '$hashedPassword', '$user_type')";

        if (mysqli_query($conn, $query)) {
            header('Location: login.php?registered=true');
            exit();
        } else {
            $error_message = "Failed to register. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Front-end validation
        function validateForm() {
            const email = document.forms["registerForm"]["email"].value;
            const password = document.forms["registerForm"]["password"].value;
            const fullName = document.forms["registerForm"]["full_name"].value;

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please provide a valid email address.");
                return false;
            }
            if (password.length < 8 || password.length > 64) {
                alert("Password must be between 8 and 64 characters.");
                return false;
            }
            if (fullName.length < 3 || fullName.length > 50) {
                alert("Full name must be between 3 and 50 characters.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="register-form">
        <h2>Register</h2>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        <form name="registerForm" method="POST" action="register.php" onsubmit="return validateForm()">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="user_type">User Type:</label>
            <select name="user_type" required>
                <option value="employee">Employee</option>
                <option value="job_seeker">Job Seeker</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
