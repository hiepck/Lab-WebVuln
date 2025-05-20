<?php
// Database connection file
// Intentionally vulnerable to SQL Injection

require_once '../config/config.php';

function db_connect() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

// Intentionally vulnerable query function (no prepared statements)
function query($sql) {
    $conn = db_connect();
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        if (DEBUG) {
            die("Query failed: " . mysqli_error($conn) . "<br>SQL: " . $sql);
        } else {
            die("An error occurred. Please try again later.");
        }
    }
    
    return $result;
}

// Get a single record
function get_record($sql) {
    $result = query($sql);
    return mysqli_fetch_assoc($result);
}

// Get multiple records
function get_records($sql) {
    $result = query($sql);
    $records = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $records[] = $row;
    }
    
    return $records;
}

// Function for insecure direct authentication
// Vulnerable to SQL injection
function authenticate($username, $password) {
    $md5_password = md5($password); // Weak hashing
    
    // Vulnerable to SQL injection
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$md5_password'";
    
    $user = get_record($sql);
    return $user;
}
?> 