<?php
require_once 'db.php';

// Start session
function start_session() {
    session_start();
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Clean input - intentionally weak for XSS
function clean_input($data) {
    return trim($data);
}

// Generate account number
function generate_account_number() {
    return mt_rand(1000000000, 9999999999);
}

// Format amount with currency
function format_amount($amount, $currency = 'USD') {
    return number_format($amount, 2) . ' ' . $currency;
}

// Upload file - intentionally vulnerable for RCE
function upload_file($file) {
    // No validation for file type or extension - allows uploading any file
    $target_dir = __DIR__ . "/../uploads/";
    $target_file = $target_dir . basename($file["name"]);
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return basename($file["name"]);
    } else {
        return false;
    }
}

// Get user by ID
function get_user($user_id) {
    $sql = "SELECT * FROM users WHERE id = $user_id";
    return get_record($sql);
}

// Get account by ID
function get_account($account_id) {
    $sql = "SELECT * FROM accounts WHERE id = $account_id";
    return get_record($sql);
}

// Get user accounts
function get_user_accounts($user_id) {
    $sql = "SELECT * FROM accounts WHERE user_id = $user_id";
    return get_records($sql);
}

// Get transactions
function get_transactions($account_id) {
    // Vulnerable to SQL Injection
    $sql = "SELECT t.*, 
            a1.account_number as from_account_number,
            a2.account_number as to_account_number
            FROM transactions t
            LEFT JOIN accounts a1 ON t.from_account_id = a1.id
            LEFT JOIN accounts a2 ON t.to_account_id = a2.id
            WHERE t.from_account_id = $account_id OR t.to_account_id = $account_id
            ORDER BY t.created_at DESC";
    return get_records($sql);
}

// Create transaction
function create_transaction($from_account_id, $to_account_id, $amount, $type, $description) {
    $sql = "INSERT INTO transactions (from_account_id, to_account_id, amount, transaction_type, description, status) 
            VALUES ($from_account_id, $to_account_id, $amount, '$type', '$description', 'completed')";
    query($sql);
    
    // Update account balances
    if ($from_account_id) {
        $sql = "UPDATE accounts SET balance = balance - $amount WHERE id = $from_account_id";
        query($sql);
    }
    
    if ($to_account_id) {
        $sql = "UPDATE accounts SET balance = balance + $amount WHERE id = $to_account_id";
        query($sql);
    }
}

// Get messages for user
function get_user_messages($user_id) {
    $sql = "SELECT * FROM messages WHERE user_id = $user_id ORDER BY created_at DESC";
    return get_records($sql);
}

// Create message
function create_message($user_id, $subject, $content) {
    $subject = mysqli_real_escape_string(db_connect(), $subject);
    $content = mysqli_real_escape_string(db_connect(), $content);
    
    $sql = "INSERT INTO messages (user_id, subject, content) VALUES ($user_id, '$subject', '$content')";
    query($sql);
}
?> 