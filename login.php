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
$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input values
    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);
    
    // Authenticate user (vulnerable to SQL injection)
    $user = authenticate($username, $password);
    
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        // Update last login
        $user_id = $user['id'];
        $sql = "UPDATE users SET last_login = NOW() WHERE id = $user_id";
        query($sql);
        
        // Redirect to dashboard
        redirect('dashboard.php');
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vulnerable Banking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2>Vulnerable Banking System</h2>
            <h3>Login</h3>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html> 