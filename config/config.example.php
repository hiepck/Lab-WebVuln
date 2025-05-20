<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'vbank_user');
define('DB_PASS', 'vbank_password');
define('DB_NAME', 'vbank');

// Application settings
define('APP_NAME', 'Vulnerable Banking System');
define('APP_URL', 'http://localhost'); // Change to your server URL
define('UPLOAD_DIR', '../uploads/');
define('MAX_FILE_SIZE', 5000000); // 5MB

// Session settings
define('SESSION_TIME', 3600); // 1 hour

// Debug mode (set to false in production)
define('DEBUG', true);
?> 