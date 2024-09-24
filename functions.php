<?php
// functions.php
session_start();

// Database connection using mysqli_connect
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'job_portal';
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Common function to sanitize inputs
function sanitize($data) {
    global $conn;
    return htmlspecialchars(mysqli_real_escape_string($conn, $data));
}

// Login function
function login($email, $password) {
    global $conn;

    $email = sanitize($email);
    $password = sanitize($password);

    // Secure password hashing (replace md5 with password_hash in production)
    $hashedPassword = md5($password); 
    $query = "SELECT * FROM users WHERE email='$email' AND password='$hashedPassword'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        
        // If "Remember Me" checked, create cookie
        if (isset($_POST['remember_me'])) {
            $cookie_value = $user['id'] . '.' . sha1(time());
            setcookie('user_id', $cookie_value, time() + (86400 * 30), "/"); // 30 days
            save_cookie($user['id'], $cookie_value);
        }
        return true;
    } else {
        return false;
    }
}

// Save cookie in the database
function save_cookie($user_id, $cookie_value) {
    global $conn;
    $expiry_date = date("Y-m-d H:i:s", time() + (86400 * 30)); // 30 days
    $query = "INSERT INTO cookies (user_id, cookie_value, expiry_date) 
              VALUES ('$user_id', '$cookie_value', '$expiry_date')
              ON DUPLICATE KEY UPDATE cookie_value='$cookie_value', expiry_date='$expiry_date'";
    mysqli_query($conn, $query);
}

// Check if user is logged in via session or cookies
function is_logged_in() {
    global $conn;
    if (isset($_SESSION['user_id'])) {
        return true;
    } elseif (isset($_COOKIE['user_id'])) {
        $cookie_value = $_COOKIE['user_id'];
        $query = "SELECT * FROM cookies WHERE cookie_value='$cookie_value' AND expiry_date > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $cookie_data = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $cookie_data['user_id'];
            $_SESSION['user_type'] = get_user_type($cookie_data['user_id']);
            return true;
        }
    }
    return false;
}

// Get user type from user_id
function get_user_type($user_id) {
    global $conn;
    $query = "SELECT user_type FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    return $user['user_type'];
}

// Logout function to clear session and cookies
function logout() {
    session_unset();
    session_destroy();

    // Clear cookie
    if (isset($_COOKIE['user_id'])) {
        unset($_COOKIE['user_id']);
        setcookie('user_id', '', time() - 3600, '/');
    }
}

// Function to post a job (for employer)
function post_job($title, $position, $salary, $description, $location, $requirements, $benefits) {
    global $conn;
    $title = sanitize($title);
    $position = sanitize($position);
    $salary = sanitize($salary);
    $description = sanitize($description);
    $location = sanitize($location);
    $requirements = sanitize($requirements);
    $benefits = sanitize($benefits);

    $employer_id = $_SESSION['user_id']; // Assuming the user is an employer
    $query = "INSERT INTO jobs (employer_id, title, position, salary, description, location, requirements, benefits)
              VALUES ('$employer_id', '$title', '$position', '$salary', '$description', '$location', '$requirements', '$benefits')";
    return mysqli_query($conn, $query);
}

// Function to fetch all job posts for display on the dashboard
function get_all_jobs() {
    global $conn;
    $query = "SELECT * FROM jobs ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

// Function to fetch a single job details
function get_job_details($job_id) {
    global $conn;
    $query = "SELECT * FROM jobs WHERE id='$job_id'";
    return mysqli_query($conn, $query);
}