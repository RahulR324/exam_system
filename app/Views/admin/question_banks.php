<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Question Banks</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>

    <style>
        .btn-disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg">
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

                <div class="admin-dropdown ms-auto">
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

                <div class="category-header mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">Question Banks</h2>
                        <p class="text-muted mb-0">Manage question banks and questions</p>
                    </div>

                    <div class="d-flex justify-content-end align-items-center gap-2 mb-4">

                        <a href="<?= base_url('admin/question_banks') ?>"
                           class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>

                        <?php if (!empty($parentId)): ?>
                            <a href="<?= base_url('admin/add_question/'.$parentId) ?>"
                               class="btn btn-outline-success rounded-pill px-4">
                                <i class="fas fa-question-circle me-2"></i> Add Question
                            </a>
                        <?php endif; ?>

                        <a href="<?= base_url('admin/add_question_bank'.($parentId ? '?parent='.$parentId : '')) ?>"
                           class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i> Add Question Bank
                        </a>

                    </div>
                </div>

                <div class="search-wrapper mb-4">
                    <form method="get" action="<?= current_url() ?>" class="search-box">

                        <i class="fas fa-search search-icon"></i>

                        <input
                            type="text"
                            name="search"
                            class="search-input"
                            placeholder="Search questions from all Question Banks..."
                            value="<?= esc($_GET['search'] ?? '') ?>">

                        <?php if (!empty($_GET['search'])): ?>
                            <a href="<?= current_url() ?>" class="clear-btn rounded-pill">
                                Clear
                            </a>
                        <?php endif; ?>

                    </form>
                </div>

                <?php if (!empty($_GET['search'])): ?>
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-search me-2"></i>
                        Showing results for:
                        <strong><?= esc($_GET['search']) ?></strong>
                    </div>
                <?php endif; ?>

                <div class="breadcrumb-container">

                    <a href="<?= base_url('admin/question_banks') ?>"
                       class="breadcrumb-home">
                        <i class="fas fa-folder-tree"></i>
                        Question Banks
                    </a>

                    <?php if (!empty($path)): ?>
                        <?php foreach ($path as $node): ?>
                            <span class="breadcrumb-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </span>

                            <a href="<?= base_url('admin/question_banks/'.$node['questionbank_id']) ?>"
                               class="breadcrumb-pill">
                                <?= esc($node['questionbank_name']) ?>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

                <?php if (!empty($banks) || !empty($questions)): ?>

                    <?php if (!empty($banks) && empty($_GET['search'])): ?>

                        <div class="subject-grid mb-5">

                            <?php foreach ($banks as $bank): ?>

                                <div class="subject-card-modern">

                                    <a class="node-link text-decoration-none"
                                       href="<?= base_url('admin/question_banks/'.$bank['questionbank_id']) ?>">

                                        <div class="subject-card-header">

                                            <div class="folder-badge">
                                                <i class="fas fa-folder-open"></i>
                                            </div>

                                            <div class="subject-header-content">
                                                <h5 class="subject-name">
                                                    <?= esc($bank['questionbank_name']) ?>
                                                </h5>
                                            </div>

                                        </div>

                                    </a>

                                    <div class="subject-card-body">

                                        <div class="action-row">

                                            <a href="<?= base_url('admin/edit_question_bank/'.$bank['questionbank_id']) ?>"
                                               class="btn btn-outline-primary rounded-pill">

                                                <i class="fas fa-pen"></i>
                                                Edit

                                            </a>

                                            <a href="<?= base_url('admin/delete_question_bank/'.$bank['questionbank_id']) ?>"
                                               class="btn btn-outline-danger rounded-pill"
                                               onclick="return confirm('Delete this Question Bank?')">

                                                <i class="fas fa-trash"></i>
                                                Delete

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                    <?php if (!empty($questions)): ?>

                        <div class="section-heading mb-4">

                            <h4 class="fw-bold mb-1">

                                <?php if (!empty($_GET['search'])): ?>
                                    Search Results
                                <?php else: ?>
                                    <?= esc($path[count($path)-1]['questionbank_name'] ?? 'Questions') ?>
                                <?php endif; ?>

                            </h4>

                            <div class="section-divider"></div>

                        </div>

                        <div class="card border-0 shadow-sm">

                            <div class="table-responsive">

                                <table class="table align-middle mb-0 question-table">

                                    <thead>
                                        <tr>
                                            <th width="80">Id</th>
                                            <th>Question</th>
                                            <th width="220">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php $sl = 1; ?>

                                        <?php foreach ($questions as $question): ?>

                                            <tr class="question-row">

                                                <td>
                                                    <div class="question-number">
                                                        <?= $sl++ ?>
                                                    </div>
                                                </td>

                                                <td>

                                                    <?php if (!empty($_GET['search'])): ?>
                                                        <div class="mb-2">
                                                            <span class="badge bg-light text-success">
                                                                <?= esc($question['questionbank_name'] ?? 'Question Bank') ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="question-title mb-3">
                                                        <?= strip_tags($question['question_text']) ?>
                                                    </div>

                                                    <div class="options-list">

                                                        <?php foreach (['A','B','C','D'] as $opt): ?>

                                                            <div class="option-item <?= $question['correct_answer'] == $opt ? 'correct-option' : '' ?>">

                                                                <?php if($question['correct_answer'] == $opt): ?>
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                <?php endif; ?>

                                                                <strong><?= $opt ?>.</strong>

                                                                <?= esc($question['option_' . strtolower($opt)]) ?>

                                                            </div>

                                                        <?php endforeach; ?>

                                                    </div>

                                                </td>

                                                <td class="action-cell">

                                                    <div class="action-buttons">

                                                        <a href="<?= base_url('admin/edit_question/'.$question['question_id']) ?>"
                                                           class="btn btn-outline-primary btn-sm rounded-pill">

                                                            <i class="fas fa-pen"></i>
                                                            Edit

                                                        </a>

                                                        <a href="<?= base_url('admin/delete_question/'.$question['question_id']) ?>"
                                                           class="btn btn-outline-danger btn-sm rounded-pill"
                                                           onclick="return confirm('Delete this Question?')">

                                                            <i class="fas fa-trash"></i>
                                                            Delete

                                                        </a>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    <?php endif; ?>

                <?php else: ?>

                    <div class="empty-box text-center py-5">

                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>

                        <h4>No Content Available</h4>

                        <p class="text-muted mb-0">
                            No Question Banks or Questions found.
                        </p>

                    </div>

                <?php endif; ?>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
    <script src="/js/admin.js"></script>
</body>
</html>