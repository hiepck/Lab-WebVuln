<?php
// Include required files
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Start session
start_session();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirect('login.php');
}

// Get user information
$user_id = $_SESSION['user_id'];
$user = get_user($user_id);

// Initialize variables
$error = '';
$success = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        // Update profile information
        $full_name = clean_input($_POST['full_name']);
        $email = clean_input($_POST['email']);
        
        // Update user in database (vulnerable to SQL injection)
        $sql = "UPDATE users SET full_name = '$full_name', email = '$email' WHERE id = $user_id";
        query($sql);
        
        // Update user variable
        $user['full_name'] = $full_name;
        $user['email'] = $email;
        
        $success = 'Profile updated successfully!';
    } elseif (isset($_POST['change_password'])) {
        // Change password
        $current_password = clean_input($_POST['current_password']);
        $new_password = clean_input($_POST['new_password']);
        $confirm_password = clean_input($_POST['confirm_password']);
        
        // Verify current password
        $md5_current = md5($current_password);
        if ($md5_current !== $user['password']) {
            $error = 'Current password is incorrect';
        } elseif ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            // Update password in database
            $md5_new = md5($new_password); // Weak hashing
            $sql = "UPDATE users SET password = '$md5_new' WHERE id = $user_id";
            query($sql);
            
            $success = 'Password changed successfully!';
        }
    } elseif (isset($_POST['upload_image'])) {
        // Handle file upload (intentionally vulnerable)
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $file_name = upload_file($_FILES['profile_image']);
            
            if ($file_name) {
                // Update user profile_image in database
                $sql = "UPDATE users SET profile_image = '$file_name' WHERE id = $user_id";
                query($sql);
                
                // Update user variable
                $user['profile_image'] = $file_name;
                
                $success = 'Profile image updated successfully!';
            } else {
                $error = 'Failed to upload profile image';
            }
        } else {
            $error = 'Please select a file to upload';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Vulnerable Banking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="profile-page">
            <h2>My Profile</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="profile-sections">
                <div class="profile-image-section">
                    <h3>Profile Image</h3>
                    <div class="profile-image">
                        <?php if ($user['profile_image']): ?>
                            <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Image">
                        <?php else: ?>
                            <div class="no-image">No Image</div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Vulnerable file upload form - allows any file type -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="profile_image">Upload New Image</label>
                            <input type="file" id="profile_image" name="profile_image">
                            <p class="form-hint">Allowed files: any file (intentionally vulnerable)</p>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                        </div>
                    </form>
                </div>
                
                <div class="profile-info-section">
                    <h3>Personal Information</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            <p class="form-hint">Username cannot be changed</p>
                        </div>
                        
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" id="role" value="<?php echo ucfirst($user['role']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                    
                    <h3>Change Password</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="back-link">
                <a href="dashboard.php">&larr; Back to Dashboard</a>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html> 