<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Exam Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <span class="navbar-title">Exam Portal</span>
        </div>
        <div class="navbar-info">
            <span class="navbar-text">Online Examination System</span>
        </div>
    </div>
</nav>

<div class="auth-container">
    <div class="login-header">
        <h1>Student Login</h1>
        <p>Welcome to the Online Examination Portal</p>
    </div>

    <div class="card login-card">
        
        <div id="alertBox" class="alert alert-error" style="display: none;">
            <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span id="alertMessage"></span>
        </div>

        <form id="loginForm" class="login-form">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input 
                    type="text"
                    id="username"
                    name="username"
                    class="form-input"
                    placeholder="Enter your username"
                    required>
                <div class="form-input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="Enter your password"
                    required>
                <div class="form-input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">
                <span>Access Portal</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
        </form>

        <div class="login-footer">
            <p>First time here? <span class="text-muted">Contact your administrator for credentials</span></p>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Stop standard form-reloading behavior
    
    const alertBox = document.getElementById('alertBox');
    const alertMessage = document.getElementById('alertMessage');
    
    // Clear display state from any older failed attempts
    alertBox.style.display = 'none';

    // Compile input values automatically (handles fields + CSRF token)
    const formData = new FormData(this);

    try {
        const response = await fetch('/login', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Helps CodeIgniter identify an AJAX pipeline
            }
        });

        const result = await response.json();

        if (response.ok && result.status === true) {
            // Save the newly minted JWT token locally in the student's browser session
            localStorage.setItem('auth_token', result.token);
            
            // Redirect the user forward to the instructions gateway page
            window.location.href = '/instructions';
        } else {
            alertMessage.textContent = result.message || 'Authentication failed.';
            alertBox.style.display = 'flex';
        }
    } catch (error) {
        console.error('Error executing authentication exchange:', error);
        alertMessage.textContent = 'A connection breakdown occurred. Please try again.';
        alertBox.style.display = 'flex';
    }
});
</script>

</body>
</html>