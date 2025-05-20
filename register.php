<?php
// Include required files
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Start session
start_session();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php');
}

// Initialize variables
$username = '';
$email = '';
$full_name = '';
$error = '';
$success = '';

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values - intentionally vulnerable to XSS
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $full_name = clean_input($_POST['full_name']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    
    // Minimal validation
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if username exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $user = get_record($sql);
        
        if ($user) {
            $error = 'Username already exists';
        } else {
            // Check if email exists
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $user = get_record($sql);
            
            if ($user) {
                $error = 'Email already exists';
            } else {
                // Create user (vulnerable to SQL injection)
                $md5_password = md5($password); // Weak hashing
                $sql = "INSERT INTO users (username, password, email, full_name, role) 
                        VALUES ('$username', '$md5_password', '$email', '$full_name', 'user')";
                query($sql);
                
                // Get the new user ID
                $user_id = mysqli_insert_id(db_connect());
                
                // Create default account
                $account_number = generate_account_number();
                $sql = "INSERT INTO accounts (user_id, account_number, account_type, balance, currency) 
                        VALUES ($user_id, '$account_number', 'savings', 100.00, 'USD')";
                query($sql);
                
                // Create welcome message
                $subject = 'Welcome to Vulnerable Banking System';
                $content = 'Thank you for registering with our banking system. Your account has been credited with $100.00 as a welcome bonus.';
                create_message($user_id, $subject, $content);
                
                $success = 'Registration successful! You can now <a href="login.php">login</a>.';
                
                // Clear form fields
                $username = '';
                $email = '';
                $full_name = '';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Vulnerable Banking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="register-form">
            <h2>Vulnerable Banking System</h2>
            <h3>Register</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo $full_name; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
            <?php endif; ?>
            
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</body>
</html> 