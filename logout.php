<?php
require_once('functions.php');

// Log out the user
logout();

// Redirect to the landing page or login page
header('Location: index.php');
exit();
?>
