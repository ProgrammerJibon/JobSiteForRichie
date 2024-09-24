<?php
require_once('functions.php');

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($email, $password)) {
        header('Location: dashboard.php');
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Front-end validation
        function validateLogin() {
            const email = document.forms["loginForm"]["email"].value;
            const password = document.forms["loginForm"]["password"].value;

            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please provide a valid email address.");
                return false;
            }
            if (password.length < 8) {
                alert("Password must be at least 8 characters.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="login-form">
        
        
        <form name="loginForm" method="POST" action="login.php" onsubmit="return validateLogin()">

            <h2>
                <br>
                <center>Login</center>
            </h2>
            <br>
            <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label>
                <input type="checkbox" checked name="remember_me"> Remember Me
            </label>

            <br>

            <button type="submit">Login</button>

            <div>
                <center>
                    <br>
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                    <br>
                </center>
            </div>
        </form>

        
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
