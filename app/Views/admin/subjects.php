<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
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
                        <li class="nav-item">
                            <a href="/admin/dashboard" class="nav-link">
                                <i class="nav-icon fas fa-gauge-high"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/course_categories" class="nav-link">
                                <i class="nav-icon fas fa-layer-group"></i>
                                <p>Course Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/courses" class="nav-link">
                                <i class="nav-icon fas fa-book-open"></i>
                                <p>Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/exams" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-list"></i>
                                <p>Exams</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/question_banks" class="nav-link">
                                <i class="nav-icon fas fa-clipboard-question"></i>
                                <p>Question Bank</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/admin/view_students" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Students</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </aside>

        <main class="app-main">

    <div class="main-content container-fluid p-4">

        <!-- Page Header -->
        <div class="category-header mb-4">

            <div>
                <h2 class="fw-bold mb-1">Subjects</h2>

                <p class="text-muted mb-0">
                    Manage subjects for this course
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="/admin/courses"
                   class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back
                </a>

                <a href="<?= base_url('admin/add_subject/'.$course['course_id']) ?>"
                   class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-plus me-2"></i>
                    Add Subject
                </a>

            </div>

        </div>

        <!-- Success Message -->
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

        <!-- Subject Header -->
        <div class="subject-section-header">

            <span class="course-pill">
                <?= esc($course['course_name']) ?>
            </span>

            <span class="card-count-pill">
                <?= count($subjects) ?> Subject<?= count($subjects) != 1 ? 's' : '' ?>
            </span>

            <div class="cat-divider-line"></div>

        </div>

        <!-- Subjects -->
        <?php if(!empty($subjects)): ?>

            <div class="subject-grid">

                <?php foreach($subjects as $subject): ?>

                    <div class="subject-card-modern">

                        <!-- Card Header -->
                        <div class="subject-card-header">

                            <div class="folder-badge">
                                <i class="fas fa-folder-open"></i>
                            </div>

                            <div class="subject-header-content">
                                <h5 class="subject-name">
                                    <?= esc($subject['subject_name']) ?>
                                </h5>
                            </div>

                            <!-- Three Dot Menu -->
                            <div class="dropdown subject-menu">

                                <button
                                    class="menu-btn"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                    <i class="fas fa-ellipsis-v"></i>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                                    <li>
                                        <a class="dropdown-item"
                                           href="<?= base_url('admin/edit_subject/'.$subject['subject_id']) ?>">

                                            <i class="fas fa-pen me-2 text-primary"></i>
                                            Edit
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger"
                                           href="<?= base_url('admin/delete_subject/'.$subject['subject_id']) ?>"
                                           onclick="return confirm('Do you want to delete this subject?')">

                                            <i class="fas fa-trash me-2"></i>
                                            Delete

                                        </a>
                                    </li>

                                </ul>

                            </div>

                        </div>

                        <!-- Card Body -->
                        <div class="subject-card-body">
                            <a href="<?= base_url('admin/topics/'.$subject['subject_id']) ?>"
                               class="btn-act-subj rounded-pill">

                                <i class="fas fa-book-open"></i>
                                View Topics

                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>

            <div class="empty-state text-center py-5">

                <i class="fas fa-folder-open fa-3x mb-3 text-primary"></i>

                <h4>No Subjects Found</h4>

                <p class="text-muted">
                    No subjects have been added for this course yet.
                </p>

                <a href="<?= base_url('admin/add_subject/'.$course['course_id']) ?>"
                   class="btn btn-primary rounded-pill px-4">

                    <i class="fas fa-plus me-2"></i>
                    Add First Subject

                </a>

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