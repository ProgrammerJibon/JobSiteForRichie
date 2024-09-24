<!-- settings.php -->
<?php
require_once('functions.php');

// Ensure user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_field = sanitize($_POST['field']);
    $new_value = sanitize($_POST['value']);

    // Backend validation
    if ($update_field === 'email' && !filter_var($new_value, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please provide a valid email address.";
    } elseif ($update_field === 'password' && (strlen($new_value) < 8 || strlen($new_value) > 64)) {
        $error_message = "Password must be between 8 and 64 characters.";
    } elseif ($update_field === 'full_name' && (strlen($new_value) < 3 || strlen($new_value) > 50)) {
        $error_message = "Full name must be between 3 and 50 characters.";
    } else {
        // Update field in the database
        $query = "UPDATE users SET $update_field = '$new_value' WHERE id = '$user_id'";
        if (mysqli_query($conn, $query)) {
            $success_message = ucfirst($update_field) . " updated successfully!";
        } else {
            $error_message = "Failed to update " . $update_field . ". Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // Front-end validation for the settings form
        function validateSettingsForm() {
            const field = document.forms["settingsForm"]["field"].value;
            const value = document.forms["settingsForm"]["value"].value;
            
            if (field === "email") {
                const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailPattern.test(value)) {
                    alert("Please provide a valid email address.");
                    return false;
                }
            }
            if (field === "password" && (value.length < 8 || value.length > 64)) {
                alert("Password must be between 8 and 64 characters.");
                return false;
            }
            if (field === "full_name" && (value.length < 3 || value.length > 50)) {
                alert("Full name must be between 3 and 50 characters.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="settings-form">
        
        <form name="settingsForm" method="POST" action="settings.php" onsubmit="return validateSettingsForm()">
            <h2>
                <center>
                    <br>
                    Update Your Settings
                    <br>
                </center>
            </h2>
            <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
            <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
            <br>
            <label for="field">Field to Update:</label>
            <select name="field" required>
                <option value="full_name">Full Name</option>
                <option value="email">Email</option>
                <option value="password">Password</option>
            </select>

            <label for="value">New Value:</label>
            <input type="text" name="value" required>

            <button type="submit">Update</button>
        </form>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
