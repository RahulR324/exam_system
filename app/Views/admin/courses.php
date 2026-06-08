<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Course Categories - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
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
                <div class="category-header d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Courses</h2>
                        <p class="text-muted mb-0">Manage and organize courses by category</p>
                    </div>
                    <a href="/admin/add_course" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i> Add Course
                    </a>
                </div>

                <div class="course-filter-bar">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="courseSearch" placeholder="Search courses...">
                    </div>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php foreach($categories as $category): ?>
                    <div class="category-block mb-5">
                        <div class="cat-label">
                            <span class="cat-label-text"><?= esc($category['category_name']) ?></span>
                            <span class="cat-count-pill">
                                <?= count($category['courses']) ?> Course<?= count($category['courses']) != 1 ? 's' : '' ?>
                            </span>
                            <div class="cat-divider-line"></div>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                            <?php foreach($category['courses'] as $course): ?>
                                <div class="col course-item" data-course="<?= strtolower($course['course_name']) ?>" data-price="<?= $course['price'] ?>">
                                    <div class="course-card">
                                        <div class="course-thumb-wrap">
                                            <?php if (!empty($course['thumbnail'])): ?>
                                                <img src="<?= base_url('uploads/course_thumbnails/' . $course['thumbnail']) ?>" alt="<?= esc($course['course_name']) ?>">
                                            <?php else: ?>
                                                <div class="thumb-placeholder"><i class="fas fa-book-open"></i></div>
                                            <?php endif; ?>
                                            <div class="price-badge">₹<?= number_format($course['price'], 2) ?></div>
                                        </div>

                                        <div class="course-card-body">
                                            <h5 class="course-card-title"><?= esc($course['course_name']) ?></h5>
                                            <div class="card-divider"></div>
                                            <div class="action-row">
                                                <a href="<?= base_url('admin/edit_course/'.$course['course_id']) ?>" class="btn btn-outline-primary flex-fill rounded-pill px-4">
                                                    <i class="fas fa-pen"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/delete_course/'.$course['course_id']) ?>" class="btn btn-outline-danger flex-fill rounded-pill px-4" onclick="return confirm('Delete this course?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </div>
                                            <a href="<?= base_url('admin/subjects/'.$course['course_id']) ?>" class="btn-act-subj rounded-pill">
                                                <i class="fas fa-book"></i> View Subjects
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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