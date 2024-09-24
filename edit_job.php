<?php
require_once('functions.php');

// Ensure user is logged in and is an employee
if (!is_logged_in() || $_SESSION['user_type'] != 'employee') {
    header('Location: login.php');
    exit();
}

// Get job ID from URL and sanitize it
$job_id = sanitize($_GET['job_id']);

// Fetch job details
$job_details = get_job_details($job_id);
$job = mysqli_fetch_assoc($job_details);

// Check if job exists
if (!$job) {
    die("Job not found.");
}

// Initialize variables
$title = $job['title'];
$position = $job['position'];
$salary = $job['salary'];
$description = $job['description'];
$location = $job['location'];
$requirements = $job['requirements'];
$benefits = $job['benefits'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
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
        // Update the job in the database
        $query = "UPDATE jobs SET title='$title', position='$position', salary='$salary', 
                  description='$description', location='$location', 
                  requirements='$requirements', benefits='$benefits' 
                  WHERE id='$job_id'";

        if (mysqli_query($conn, $query)) {
            $success_message = "Job updated successfully!";
        } else {
            $error_message = "Failed to update job. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="edit-job-form">
        <h2>Edit Job: <?php echo htmlspecialchars($title); ?></h2>
        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
        <form method="POST" action="edit_job.php?job_id=<?php echo $job_id; ?>">
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

            <button type="submit">Update Job</button>
        </form>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
