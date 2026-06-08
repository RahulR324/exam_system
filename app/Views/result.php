<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result - Exam Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<!-- Fixed Navigation Bar -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <span class="navbar-title">Exam Portal</span>
        </div>
        <div class="navbar-info">
            <span class="navbar-text">Exam Result</span>
        </div>
    </div>
</nav>

<!-- Result Content -->
<div class="result-wrapper">
    <div class="result-container">
        <div class="card result-card">
            
            <!-- Success Icon -->
            <div class="result-icon-container">
                <div class="result-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>

            <!-- Result Message -->
            <div class="result-message">
                <h1><?= esc($message) ?></h1>
                <p>Your examination session has concluded successfully.</p>
            </div>

            <!-- Score Section -->
            <?php if(isset($score)) : ?>
                <div class="score-section">
                    <div class="score-circle">
                        <span class="score-num"><?= esc($score) ?></span>
                        <span class="score-denom">/ <?= esc($total) ?></span>
                        <span class="score-percent"><?= round(($score / $total) * 100, 1) ?>%</span>
                    </div>

                    <div class="result-details">
                        <div class="detail-item">
                            <div class="detail-label">Student Name</div>
                            <div class="detail-value"><?= esc($student['name']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Exam Title</div>
                            <div class="detail-value"><?= esc($exam['title']) ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Score</div>
                            <div class="detail-value highlight"><?= esc($score) ?> / <?= esc($total) ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-error alert-centered">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>Wait Window is closed or session is invalid.</span>
                </div>
            <?php endif; ?>

            <!-- Redirect Message -->
            <div class="redirect-section">
                <p id="redirect-msg" class="redirect-text">Redirecting to login page in <strong>5</strong> seconds...</p>
            </div>
        </div>
    </div>
</div>

<script>
    let seconds = 5;
    const msg = document.getElementById('redirect-msg');
    const countdown = setInterval(() => {
        seconds--;
        const strongTag = msg.querySelector('strong');
        strongTag.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(countdown);
            window.location.href = "/";
        }
    }, 1000);
</script>

</body>
</html>