<link rel="stylesheet" href="styles.css">
<script src="script.js" defer></script>
<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'employee') { ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="post_jobs.php">Post a Job</a></li>
                <li><a href="my_jobs.php">My Jobs</a></li> <!-- Link to My Jobs for employees -->
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'job_seeker') { ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="my_jobs.php">My Jobs</a></li> <!-- Link to My Jobs for job seekers -->
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php } ?>
        </ul>
    </nav>
</header>