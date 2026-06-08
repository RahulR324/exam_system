<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($exam['title']) ?> - Exam</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.css">
</head>
<body>

<!-- Fixed Navigation Bar -->
<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-brand">
            <span class="navbar-title">Exam Portal</span>
        </div>
        <div class="navbar-student-info">
            <div class="student-badge">
                <span class="student-name"><?= esc($student['name']) ?></span>
                <span class="student-reg">Reg: <?= esc($student['register_number']) ?></span>
            </div>
            <div class="timer-display">
                <svg class="timer-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="9"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <span id="timer" class="timer-text">--:--</span>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="exam-wrapper">
    <div class="exam-container">
        <!-- Progress Section -->
        <div class="exam-progress">
            <div class="progress-content">
                <span class="progress-label">Question <span id="question-number">1</span> of <span id="total-questions"><?= count($questions) ?></span></span>
                <div class="progress-bar-track">
                    <div id="progress-bar-fill" class="progress-bar-fill"></div>
                </div>
            </div>
        </div>

        <!-- Question Card -->
        <div class="question-container">
            <div class="question-card">
                <div id="question-text" class="question-text"></div>

                <form id="exam-form" method="post" action="<?= base_url('/exam') ?>">
                    <div class="options" id="options-container">
                        <label class="option-item">
                            <input type="radio" name="answer" id="option1" value="" class="option-radio">
                            <div class="option-content">
                                <span class="option-label">A</span>
                                <span id="opt1-text" class="option-text"></span>
                            </div>
                        </label>

                        <label class="option-item">
                            <input type="radio" name="answer" id="option2" value="" class="option-radio">
                            <div class="option-content">
                                <span class="option-label">B</span>
                                <span id="opt2-text" class="option-text"></span>
                            </div>
                        </label>

                        <label class="option-item">
                            <input type="radio" name="answer" id="option3" value="" class="option-radio">
                            <div class="option-content">
                                <span class="option-label">C</span>
                                <span id="opt3-text" class="option-text"></span>
                            </div>
                        </label>

                        <label class="option-item">
                            <input type="radio" name="answer" id="option4" value="" class="option-radio">
                            <div class="option-content">
                                <span class="option-label">D</span>
                                <span id="opt4-text" class="option-text"></span>
                            </div>
                        </label>
                    </div>
                </form>
            </div>

            <!-- Navigation & Submit Section -->
            <div class="exam-actions">
                <div class="navigation-buttons">
                    <button onclick="prevQuestion()" class="btn btn-secondary btn-nav">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Previous</span>
                    </button>

                    <button onclick="nextQuestion()" id="btn-next" class="btn btn-secondary btn-nav">
                        <span>Next</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </div>

                <button onclick="submitExam()" class="btn btn-primary btn-submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Submit Exam</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Popup -->
<div id="examPopup" class="popup-overlay" style="display:none;">
    <div class="popup-content">
        <div class="popup-header">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 9v2m0 4v2m-6.773-4h13.546a2 2 0 0 1 1.977 2.304l-1.255 6.31A2 2 0 0 1 17.268 21H6.732a2 2 0 0 1-1.977-1.386l-1.255-6.31A2 2 0 0 1 5.227 12z"></path>
            </svg>
            <h3>Submit Exam?</h3>
        </div>
        <p id="popup-message">Are you sure you want to submit your exam? This action cannot be undone.</p>
        <div class="popup-buttons">
            <button id="popup-cancel" class="btn btn-secondary">
                Cancel
            </button>
            <button id="popup-confirm" class="btn btn-primary">
                Confirm Submit
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.js"></script>

<script>
const questionsData = <?= json_encode($questions) ?>;
const examDuration = <?= $duration ?>;

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.ta-math').forEach(function(element){
        const formula = element.getAttribute('data-math');
        if(formula && window.katex){
            katex.render(formula, element, {
                throwOnError:false
            });
        }
    });
});
</script>

<script src="/js/exam.js"></script>

<script>
    initExam(questionsData, examDuration);
</script>

</body>
</html>