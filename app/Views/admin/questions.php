<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions - Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
    <link rel="stylesheet" href="/css/adminstyle.css">

    <style>
        .ta-content { line-height: 1.8; }
        .ta-content img { max-width: 100%; height: auto; }
        .ta-content table { width: 100%; border-collapse: collapse; }
        .ta-content table td, .ta-content table th { border: 1px solid #ddd; padding: 8px; }
        .ta-content .katex { font-size: 1.1rem; }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open">

<div class="app-wrapper">

    <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-lte-toggle="sidebar" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-house"></i> Dashboard</a>
                </li>
            </ul>

            <div class="admin-dropdown">
                <button class="admin-btn" onclick="toggleDropdown()">
                    <div class="admin-avatar"><i class="fas fa-user"></i></div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-content" id="adminMenu">
                    <div class="admin-info">
                        <div class="admin-avatar small"><i class="fas fa-user"></i></div>
                        <span>Hello Admin</span>
                    </div>
                    <hr>
                    <a href="/admin/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <aside class="app-sidebar">
        <div class="sidebar-brand">
            <a href="/admin/dashboard" class="brand-link text-decoration-none">
                <span class="brand-text fw-light">EXAM ADMIN</span>
            </a>
        </div>
        <div class="sidebar-wrapper">
            <nav class="mt-2">
                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
                    <li class="nav-item"><a href="/admin/dashboard" class="nav-link"><i class="nav-icon fas fa-gauge-high"></i><p>Dashboard</p></a></li>
                    <li class="nav-item"><a href="/admin/course_categories" class="nav-link"><i class="nav-icon fas fa-layer-group"></i><p>Course Categories</p></a></li>
                    <li class="nav-item"><a href="/admin/courses" class="nav-link"><i class="nav-icon fas fa-book-open"></i><p>Courses</p></a></li>
                    <li class="nav-item"><a href="/admin/exams" class="nav-link"><i class="nav-icon fas fa-clipboard-list"></i><p>Exams</p></a></li>
                    <li class="nav-item"><a href="/admin/question_banks" class="nav-link"><i class="nav-icon fas fa-clipboard-question"></i><p>Question Bank</p></a></li>
                    <li class="nav-item"><a href="/admin/view_students" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Students</p></a></li>
                </ul>
            </nav>
        </div>
    </aside>

    <main class="app-main">
    <div class="main-content container-fluid p-4">

        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">
                    <?= esc($questionBank['questionbank_name']) ?>
                </h1>
                <p class="page-subtitle text-muted mb-0">
                    Manage evaluation questions assigned to this question bank
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="<?= base_url('admin/question_banks') ?>"
                   class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>

                <a href="<?= base_url('admin/add_question/'.$questionBank['questionbank_id']) ?>"
                   class="btn btn-outline-primary rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Add Question
                </a>
            </div>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">

                <table class="table align-middle mb-0 question-table">

                    <thead>
                        <tr>
                            <th width="90">ID</th>
                            <th>Question</th>
                            <th width="220" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if(!empty($questions)): ?>

                            <?php foreach($questions as $question): ?>

                                <tr class="question-row">

                                    <!-- Question ID -->
                                    <td>
                                        <div class="question-number">
                                            <?= $question['question_id'] ?>
                                        </div>
                                    </td>

                                    <!-- Question + Options -->
                                    <td>

                                        <div class="question-title mb-3">
                                            <?= strip_tags($question['question_text']) ?>
                                        </div>

                                        <div class="question-options">

                                            <div class="option-line <?= $question['correct_answer'] == 'A' ? 'correct-option' : '' ?>">
                                                <?php if($question['correct_answer'] == 'A'): ?>
                                                    <i class="fas fa-circle-check"></i>
                                                <?php endif; ?>

                                                <strong>A.</strong>
                                                <?= esc($question['option_a']) ?>
                                            </div>

                                            <div class="option-line <?= $question['correct_answer'] == 'B' ? 'correct-option' : '' ?>">
                                                <?php if($question['correct_answer'] == 'B'): ?>
                                                    <i class="fas fa-circle-check"></i>
                                                <?php endif; ?>

                                                <strong>B.</strong>
                                                <?= esc($question['option_b']) ?>
                                            </div>

                                            <div class="option-line <?= $question['correct_answer'] == 'C' ? 'correct-option' : '' ?>">
                                                <?php if($question['correct_answer'] == 'C'): ?>
                                                    <i class="fas fa-circle-check"></i>
                                                <?php endif; ?>

                                                <strong>C.</strong>
                                                <?= esc($question['option_c']) ?>
                                            </div>

                                            <div class="option-line <?= $question['correct_answer'] == 'D' ? 'correct-option' : '' ?>">
                                                <?php if($question['correct_answer'] == 'D'): ?>
                                                    <i class="fas fa-circle-check"></i>
                                                <?php endif; ?>

                                                <strong>D.</strong>
                                                <?= esc($question['option_d']) ?>
                                            </div>

                                        </div>

                                        <?php if(!empty($question['explanation'])): ?>
                                            <div class="mt-3 explanation-box">
                                                <strong>Explanation:</strong><br>
                                                <?= $question['explanation'] ?>
                                            </div>
                                        <?php endif; ?>

                                    </td>

                                    <!-- Actions -->
                                    <td class="text-center align-middle">

                                        <div class="action-buttons">

                                            <a href="<?= base_url('admin/edit_question/'.$question['question_id']) ?>"
                                               class="btn btn-outline-primary btn-sm rounded-pill">
                                                <i class="fas fa-pen me-1"></i>
                                                Edit
                                            </a>

                                            <a href="<?= base_url('admin/delete_question/'.$question['question_id']) ?>"
                                               class="btn btn-outline-danger btn-sm rounded-pill"
                                               onclick="return confirm('Delete this question?')">
                                                <i class="fas fa-trash me-1"></i>
                                                Delete
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="fas fa-circle-question fa-3x text-muted mb-3"></i>
                                    <h5>No Questions Found</h5>
                                    <p class="text-muted mb-0">
                                        Start by adding your first question.
                                    </p>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>
        </div>

    </div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/contrib/auto-render.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof renderMathInElement !== "undefined") {
        document.querySelectorAll('.ta-content').forEach(function(el){
            renderMathInElement(el, {
                delimiters: [
                    {left: "$$", right: "$$", display: true},
                    {left: "$", right: "$", display: false},
                    {left: "\\(", right: "\\)", display: false},
                    {left: "\\[", right: "\\]", display: true}
                ]
            });
        });
    }
});
</script>

</body>
</html>