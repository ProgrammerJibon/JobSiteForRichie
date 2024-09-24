<?php
session_start();
$userType = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
    <title>Job Portal</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if ($userType === 'employee') { ?>
                <li><a href="post_jobs.php">Post Jobs</a></li>
            <?php } elseif ($userType === 'job_seeker') { ?>
                <li><a href="search_jobs.php">Search Jobs</a></li>
            <?php } ?>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
