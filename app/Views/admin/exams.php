<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Exams - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"/>
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

                <div class="category-header mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Exams</h2>
                        <p class="text-muted mb-0">Manage and schedule assessments for your courses</p>
                    </div>
                    <a href="/admin/add_exam" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i> Add Exam
                    </a>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="info-card mb-4">
                    <form method="get">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php foreach($categories_filter as $cat): ?>
                                        <option value="<?= $cat['category_id'] ?>" <?= (isset($selectedCategory) && $selectedCategory == $cat['category_id']) ? 'selected' : '' ?>>
                                            <?= esc($cat['category_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Course</label>
                                <select name="course_id" class="form-select">
                                    <option value="">All Courses</option>
                                    <?php foreach($courses_filter as $course): ?>
                                        <option value="<?= $course['course_id'] ?>" <?= (isset($selectedCourse) && $selectedCourse == $course['course_id']) ? 'selected' : '' ?>>
                                            <?= esc($course['course_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter"></i></button>
                                <a href="<?= base_url('admin/exams') ?>" class="btn btn-outline-secondary w-100"><i class="fas fa-rotate-right"></i></a>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (!empty($courses)): ?>
                    <?php foreach($courses as $course): ?>
                        <div class="mb-5">
                            <div class="cat-label">
                            <span class="cat-label-text" style="font-size: 22px; font-weight: 500;"><?= esc($course['course_name']) ?></span>
                            <div class="cat-divider-line"></div>
                        </div>

                            <?php if (!empty($course['exams'])): ?>
                                <div class="subject-grid">
                                    <?php foreach($course['exams'] as $exam): ?>
                                        <div class="exam-card">
                                            <div class="exam-card-header">
                                                <div class="exam-icon"><i class="fas fa-file-signature"></i></div>
                                                <div><h5 class="exam-title"><?= esc($exam['title']) ?></h5></div>
                                            </div>
                                            <div class="exam-details">
                                                <div class="exam-detail"><i class="fas fa-calendar-alt"></i> <span><?= date('d M Y', strtotime($exam['date'])) ?></span></div>
                                                <div class="exam-detail"><i class="fas fa-clock"></i> <span><?= date('h:i A', strtotime($exam['start_time'])) ?> - <?= date('h:i A', strtotime($exam['end_time'])) ?></span></div>
                                                <div class="exam-detail"><i class="fas fa-hourglass-half"></i> <span><?= esc($exam['duration']) ?> Minutes</span></div>
                                            </div>
                                            <div class="exam-actions">
                                                <a href="<?= base_url('admin/edit_exam/'.$exam['exam_id']) ?>" class="btn btn-outline-primary rounded-pill w-100"><i class="fas fa-pen"></i> Edit</a>
                                                <a href="<?= base_url('admin/delete_exam/'.$exam['exam_id']) ?>" class="btn btn-outline-danger rounded-pill w-100" onclick="return confirm('Delete this exam?')"><i class="fas fa-trash"></i> Delete</a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-light border">No exams available for this course.</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="info-card text-center py-5">
                        <i class="fas fa-folder-open fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No exams found</h5>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
    <script src="/js/admin.js"></script>
</body>
</html>