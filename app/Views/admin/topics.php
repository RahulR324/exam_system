<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Topics - Admin</title>

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
                        <h2 class="fw-bold mb-1">Topics</h2>
                        <p class="text-muted mb-0">Manage topics for this subject</p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('admin/subjects/'.$subject['course_id']) ?>" class="btn btn-outline-secondary rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="<?= base_url('admin/add_topic/'.$subject['subject_id']) ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i> Add Topic
                        </a>
                    </div>
                </div>

                <div class="cat-label mb-4">
                    <h5 class="cat-label-text mb-0"><?= esc($subject['subject_name']) ?></h5>
                    <span class="cat-count-pill"><?= count($topics) ?> Topic<?= count($topics) != 1 ? 's' : '' ?></span>
                    <div class="cat-divider-line"></div>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(!empty($topics)): ?>
                    <div class="info-card p-0 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table topic-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="100">ID</th>
                                        <th>Topic Name</th>
                                        <th>Materials</th>
                                        <th width="350" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topics as $topic): ?>
                                        <tr>
                                            <td><span class="id-pill"><?= $topic['topic_id'] ?></span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <h6 class="mb-0 fw-semibold"><?= esc($topic['topic_name']) ?></h6>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge cat-count-pill text-secondary">
                                                    <?= $topic['material_count'] ?> items
                                                </span>
                                            </td>
                                
                                            <td>
                                                <div class="d-flex justify-content-center gap-1">

                                                    <a href="<?= base_url('admin/topic_materials/'.$topic['topic_id']) ?>"
                                                        class="btn btn-outline-success btn-sm rounded-pill px-2 py-1">
                                                        <i class="fas fa-photo-film me-1"></i>Materials
                                                    </a>

                                                    <a href="<?= base_url('admin/edit_topic/'.$topic['topic_id']) ?>"
                                                        class="btn btn-outline-primary btn-sm rounded-pill px-2 py-1">
                                                        <i class="fas fa-pen me-1"></i>Edit
                                                    </a>

                                                    <a href="<?= base_url('admin/delete_topic/'.$topic['topic_id']) ?>"
                                                        class="btn btn-outline-danger btn-sm rounded-pill px-2 py-1"
                                                        onclick="return confirm('Delete this topic?')">
                                                        <i class="fas fa-trash me-1"></i>Delete
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="info-card text-center py-5">
                        <div style="font-size:70px; color:#c7d2fe;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4 class="fw-bold mt-3">No Topics Available</h4>
                        <p class="text-muted mb-4">Create your first topic for this subject.</p>
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