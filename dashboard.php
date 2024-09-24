<!-- dashboard.php -->
<?php
require_once('functions.php');

// Ensure user is logged in
if (!is_logged_in()) {
    header('Location: login.php');
    exit();
}

// Fetch all jobs for display
$jobs = get_all_jobs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php require_once('header.php'); ?>

    <div class="dashboard">
        <h2>Job Listings</h2>
        <?php if (mysqli_num_rows($jobs) > 0) { ?>
            <ul class="job-list">
                <?php while ($job = mysqli_fetch_assoc($jobs)) { ?>
                    <li class="job-item">
                        <a href="job_details.php?job_id=<?php echo $job['id']; ?>">
                            <h3><?php echo $job['title']; ?></h3>
                        </a>
                        <p><?php echo mb_substr($job['description'], 0, 150) . '...'; ?></p>
                        <p><strong>Position:</strong> <?php echo $job['position']; ?></p>
                        <p><strong>Salary:</strong> <?php echo $job['salary']; ?></p>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No job postings available at the moment.</p>
        <?php } ?>
    </div>

    <?php require_once('footer.php'); ?>
</body>
</html>
