<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Instructions - <?= esc($exam['title']); ?></title>
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
            <span class="navbar-text"><?= esc($student['name']); ?> | Reg: <?= esc($student['register_number']); ?></span>
        </div>
    </div>
</nav>

<!-- Instructions Content -->
<div class="instructions-wrapper">
    <div class="instructions-container">
        <div class="card instructions-card">
            
            <!-- Header Section -->
            <div class="instructions-header">
                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"></path>
                    </svg>
                </div>
                <div class="header-content">
                    <h1><?= esc($exam['title']); ?></h1>
                    <p>Please read the following instructions carefully before starting the exam.</p>
                </div>
            </div>

            <!-- Exam Overview Section -->
            <div class="instructions-section">
                <div class="section-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <h2>Exam Overview</h2>
                </div>

                <div class="exam-details-grid">
                    <div class="detail-box">
                        <div class="detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="detail-info">
                            <span class="detail-label">Total Duration</span>
                            <span class="detail-value"><?= esc($exam['duration']); ?> Minutes</span>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="detail-info">
                            <span class="detail-label">Exam Date</span>
                            <span class="detail-value"><?= esc($exam['date']); ?></span>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"></path>
                            </svg>
                        </div>
                        <div class="detail-info">
                            <span class="detail-label">Start Window</span>
                            <span class="detail-value"><?= date('h:i A', strtotime($exam['start_time'])); ?></span>
                        </div>
                    </div>

                    <div class="detail-box">
                        <div class="detail-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"></path>
                            </svg>
                        </div>
                        <div class="detail-info">
                            <span class="detail-label">End Window</span>
                            <span class="detail-value"><?= date('h:i A', strtotime($exam['end_time'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crucial Rules Section -->
            <div class="instructions-section">
                <div class="section-header alert-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    <h2>Crucial Rules</h2>
                </div>

                <ul class="rules-list">
                    <li class="rule-item">
                        <div class="rule-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="rule-text">
                            <strong>Cannot Pause Timer:</strong> Once you start the exam, the countdown timer cannot be paused or stopped.
                        </div>
                    </li>

                    <li class="rule-item">
                        <div class="rule-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="rule-text">
                            <strong>No Navigation Away:</strong> Do not refresh, go back, or close the tab during the exam.
                        </div>
                    </li>

                    <li class="rule-item">
                        <div class="rule-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="rule-text">
                            <strong>Stable Connection:</strong> Ensure you have a stable internet connection before starting.
                        </div>
                    </li>

                    <li class="rule-item">
                        <div class="rule-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="rule-text">
                            <strong>Timer Starts on Click:</strong> The timer will start only after you click "Start Exam Now".
                        </div>
                    </li>
                    <li class="rule-item">
                        <div class="rule-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="rule-text">
                            <strong>Late Arrival Policy:</strong> Arriving late will not extend your session; coming late will directly reduce your total exam duration time.
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Start Button -->
            <div class="instructions-footer">
                <a href="/exam" class="btn btn-primary btn-large">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    <span>Start Exam Now</span>
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>