<?php
// Include required files
require_once 'includes/functions.php';

// Start session
start_session();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
redirect('login.php?message=You have been successfully logged out.');
?> 