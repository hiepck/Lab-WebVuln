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

// Get user accounts
$accounts = get_user_accounts($user_id);

// Get unread messages count
$sql = "SELECT COUNT(*) as count FROM messages WHERE user_id = $user_id AND is_read = 0";
$result = get_record($sql);
$unread_messages = $result['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Vulnerable Banking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <div class="dashboard">
            <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
            
            <div class="accounts-section">
                <h3>Your Accounts</h3>
                
                <?php if (count($accounts) > 0): ?>
                    <div class="accounts-list">
                        <?php foreach ($accounts as $account): ?>
                            <div class="account-card">
                                <h4><?php echo ucfirst($account['account_type']); ?> Account</h4>
                                <div class="account-number">Account #: <?php echo $account['account_number']; ?></div>
                                <div class="account-balance">Balance: <?php echo format_amount($account['balance'], $account['currency']); ?></div>
                                <div class="account-actions">
                                    <a href="account_details.php?id=<?php echo $account['id']; ?>" class="btn btn-sm btn-primary">Details</a>
                                    <a href="transfer.php?from=<?php echo $account['id']; ?>" class="btn btn-sm btn-success">Transfer</a>
                                    <a href="deposit.php?account=<?php echo $account['id']; ?>" class="btn btn-sm btn-info">Deposit</a>
                                    <a href="withdraw.php?account=<?php echo $account['id']; ?>" class="btn btn-sm btn-warning">Withdraw</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">You don't have any accounts yet.</div>
                <?php endif; ?>
                
                <div class="create-account">
                    <a href="create_account.php" class="btn btn-primary">Open New Account</a>
                </div>
            </div>
            
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <a href="profile.php" class="btn btn-secondary">My Profile</a>
                    <a href="messages.php" class="btn btn-secondary">
                        Messages
                        <?php if ($unread_messages > 0): ?>
                            <span class="badge"><?php echo $unread_messages; ?></span>
                        <?php endif; ?>
                    </a>
                    <?php if (is_admin()): ?>
                        <a href="admin/index.php" class="btn btn-danger">Admin Panel</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html> 