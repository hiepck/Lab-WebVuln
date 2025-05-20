<footer class="main-footer">
    <div class="container">
        <div class="footer-content">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> Vulnerable Banking System - For Educational Purposes Only
            </div>
            <div class="footer-links">
                <a href="about.php">About</a>
                <a href="terms.php">Terms of Service</a>
                <a href="privacy.php">Privacy Policy</a>
                <!-- Hidden admin path - security through obscurity vulnerability -->
                <a href="admin/backdoor.php?key=s3cr3t" style="color: transparent;">Admin</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Example of DOM-based XSS vulnerability
    document.addEventListener('DOMContentLoaded', function() {
        // Get URL parameters - vulnerable to XSS
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('message')) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'alert alert-info';
            // Vulnerable: directly inserting parameter without sanitization
            messageDiv.innerHTML = urlParams.get('message');
            document.querySelector('.container').prepend(messageDiv);
        }
    });
</script> 