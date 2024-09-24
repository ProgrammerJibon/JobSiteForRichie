<!-- job_details.php -->
<?php
require_once('functions.php');

// Ensure user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Get job ID from the URL and sanitize it
$job_id = sanitize($_GET['job_id']);

// Fetch job details
$job_details = get_job_details($job_id);
$job = mysqli_fetch_assoc($job_details);

// Check if job exists
if (!$job) {
    die("Job not found.");
}

// Initialize variables
$applicant_data = [];
$has_applied = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // For job seekers applying for the job
    if ($_SESSION['user_type'] == 'job_seeker') {
        $phone = sanitize($_POST['phone']);
        $expected_salary = sanitize($_POST['expected_salary']);
        $cv = $_FILES['cv'];

        // Backend validation
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            $error_message = "Please provide a valid phone number.";
        } elseif (!is_numeric($expected_salary)) {
            $error_message = "Expected salary should be a numeric value.";
        } elseif ($cv['type'] != 'application/pdf') {
            $error_message = "Please upload a valid CV in PDF format.";
        } else {
            // Move uploaded CV to server (e.g., /uploads/)
            $cv_name = $cv['name'];
            $cv_tmp = $cv['tmp_name'];
            $cv_path = 'uploads/' . $cv_name;
            move_uploaded_file($cv_tmp, $cv_path);

            // Save application details to the database
            $query = "INSERT INTO applications (job_id, user_id, phone, expected_salary, cv_path)
                      VALUES ('$job_id', '{$_SESSION['user_id']}', '$phone', '$expected_salary', '$cv_path')";
            mysqli_query($conn, $query);
            $success_message = "Your application has been submitted successfully!";
        }
    }

    // Handle job deletion
    if ($_SESSION['user_type'] == 'employee' && isset($_POST['delete_job'])) {
        $delete_query = "DELETE FROM jobs WHERE id='$job_id'";
        if (mysqli_query($conn, $delete_query)) {
            header('Location: my_jobs.php'); // Redirect to my jobs after deletion
            exit();
        } else {
            $error_message = "Failed to delete job. Please try again.";
        }
    }
}

// Check if the user has already applied for this job
if ($_SESSION['user_type'] == 'job_seeker') {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM applications WHERE job_id='$job_id' AND user_id='$user_id'";
    $applicant_result = mysqli_query($conn, $query);
    if (mysqli_num_rows($applicant_result) > 0) {
        $applicant_data = mysqli_fetch_assoc($applicant_result);
        $has_applied = true;
    }
}

// Fetch applicants for the job (for employers)
$applicants = [];
if ($_SESSION['user_type'] == 'employee') {
    $query = "SELECT a.*, u.full_name FROM applications a JOIN users u ON a.user_id = u.id WHERE a.job_id='$job_id'";
    $applicants = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Job Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="job-details">
        <h2>
            <?php echo htmlspecialchars($job['title']); ?>
            <?php if ($_SESSION['user_type'] == 'employee' && $job['employer_id'] == $_SESSION['user_id']) { ?>
                <a href="edit_job.php?job_id=<?php echo $job['id']; ?>" class="edit-button">Edit Job</a>
            <?php } ?>
        </h2>
        <p><strong>Position:</strong> <?php echo htmlspecialchars($job['position']); ?></p>
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
        <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>

        <?php if (isset($success_message)) { echo "<p class='success'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>

        <!-- Application form for job seekers -->
        <?php if ($_SESSION['user_type'] == 'job_seeker') { ?>
            <?php if (!$has_applied) { ?>
                <h3>Apply for this Job</h3>
                <form method="POST" enctype="multipart/form-data">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" required>

                    <label for="expected_salary">Expected Salary:</label>
                    <input type="text" name="expected_salary" required>

                    <label for="cv">Upload CV (PDF only):</label>
                    <input type="file" name="cv" accept="application/pdf" required>

                    <button type="submit">Apply Now</button>
                </form>
            <?php } else { ?>
                <h3>Edit Your Application</h3>
                <form method="POST" enctype="multipart/form-data">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" value="<?php echo htmlspecialchars($applicant_data['phone']); ?>" required>

                    <label for="expected_salary">Expected Salary:</label>
                    <input type="text" name="expected_salary" value="<?php echo htmlspecialchars($applicant_data['expected_salary']); ?>" required>

                    <label for="cv">Upload New CV (PDF only):</label>
                    <input type="file" name="cv" accept="application/pdf" required>

                    <button type="submit">Update Application</button>
                </form>
            <?php } ?>
        <?php } ?>

        <!-- List of applicants for employers -->
        <?php if ($_SESSION['user_type'] == 'employee') { ?>
            <h3>Applicants</h3>
            <ul>
                <?php while ($applicant = mysqli_fetch_assoc($applicants)) { ?>
                    <li>
                        <strong><?php echo htmlspecialchars($applicant['full_name']); ?></strong>
                        <p>Phone: <?php echo htmlspecialchars($applicant['phone']); ?></p>
                        <p>Expected Salary: <?php echo htmlspecialchars($applicant['expected_salary']); ?></p>
                        <p>CV: <a href="<?php echo htmlspecialchars($applicant['cv_path']); ?>" target="_blank">View CV</a></p>
                    </li>
                <?php } ?>
            </ul>

            <!-- Delete job button -->
            <form method="POST">
                <button type="submit" name="delete_job" class="delete-button">Delete Job</button>
            </form>
        <?php } ?>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
