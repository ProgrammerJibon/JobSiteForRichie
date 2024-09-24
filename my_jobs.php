<!-- my_jobs.php -->
<?php
require_once('functions.php');

// Ensure user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Check user type
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Initialize variables
$jobs = [];

if ($user_type == 'employee') {
    // Fetch jobs posted by the employer
    $query = "SELECT * FROM jobs WHERE employer_id='$user_id'";
    $jobs = mysqli_query($conn, $query);
} elseif ($user_type == 'job_seeker') {
    // Fetch jobs applied for by the job seeker
    $query = "SELECT j.* FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.user_id='$user_id'";
    $jobs = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Jobs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="my-jobs">
        <h2>My Jobs</h2>

        <?php if (mysqli_num_rows($jobs) > 0) { ?>
            <ul class="job-list">
                <?php while ($job = mysqli_fetch_assoc($jobs)) { ?>
                    <li class="job-item">
                        <h3>
                            <a href="job_details.php?job_id=<?php echo $job['id']; ?>">
                                <?php echo htmlspecialchars($job['title']); ?>
                            </a>
                        </h3>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($job['position']); ?></p>
                        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?></p>
                        <p><strong>Description:</strong> <?php echo mb_substr(htmlspecialchars($job['description']), 0, 150) . '...'; ?></p>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        <p><strong>Posted On:</strong> <?php echo htmlspecialchars($job['created_at']); ?></p>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No jobs found.</p>
        <?php } ?>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
