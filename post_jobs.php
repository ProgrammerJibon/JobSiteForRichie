<!-- post_jobs.php -->
<?php
require_once('functions.php');

// Only allow employees to access this page
if (!is_logged_in() || $_SESSION['user_type'] != 'employee') {
    header('Location: login.php');
    exit();
}

// Initialize variables to hold posted data
$title = '';
$position = '';
$salary = '';
$description = '';
$location = '';
$requirements = '';
$benefits = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $position = sanitize($_POST['position']);
    $salary = sanitize($_POST['salary']);
    $description = sanitize($_POST['description']);
    $location = sanitize($_POST['location']);
    $requirements = sanitize($_POST['requirements']);
    $benefits = sanitize($_POST['benefits']);

    // Backend validation
    if (strlen($title) < 3 || strlen($title) > 100) {
        $error_message = "Job title must be between 3 and 100 characters.";
    } elseif (strlen($position) < 3 || strlen($position) > 50) {
        $error_message = "Position must be between 3 and 50 characters.";
    } elseif (strlen($salary) < 1 || strlen($salary) > 20) {
        $error_message = "Salary range must be provided.";
    } elseif (strlen($description) < 10) {
        $error_message = "Job description must be at least 10 characters long.";
    } elseif (strlen($location) < 3 || strlen($location) > 100) {
        $error_message = "Location must be between 3 and 100 characters.";
    } else {
        // If validation passes, post the job
        if (post_job($title, $position, $salary, $description, $location, $requirements, $benefits)) {
            $success_message = "Job posted successfully!";
            // Clear input fields after successful submission
            $title = $position = $salary = $description = $location = $requirements = $benefits = '';
        } else {
            $error_message = "Failed to post job. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="post-job-form">
        <h2>Post a Job</h2>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        <form method="POST" action="post_jobs.php">
            <label for="title">Job Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

            <label for="position">Position:</label>
            <input type="text" name="position" value="<?php echo htmlspecialchars($position); ?>" required>

            <label for="salary">Salary Range:</label>
            <input type="text" name="salary" value="<?php echo htmlspecialchars($salary); ?>" required>

            <label for="description">Job Description:</label>
            <textarea name="description" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="location">Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($location); ?>" required>

            <label for="requirements">Requirements:</label>
            <textarea name="requirements" required><?php echo htmlspecialchars($requirements); ?></textarea>

            <label for="benefits">Benefits:</label>
            <textarea name="benefits" required><?php echo htmlspecialchars($benefits); ?></textarea>

            <button type="submit">Post Job</button>
        </form>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
