<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="dashboard.php">Vulnerable Banking System</a>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <nav class="main-nav">
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="accounts.php">Accounts</a></li>
                        <li><a href="transfer.php">Transfer</a></li>
                        <li><a href="messages.php">Messages</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin/index.php">Admin</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                
                <div class="user-actions">
                    <span>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'User'; ?></span>
                    <a href="logout.php" class="btn btn-sm btn-outline">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header> 