<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Exam System</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css" />
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
                    <span class="brand-text fw-light">EXAM PORTAL</span>
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
            <div class="main-content">

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h1 class="page-title mb-1">Topic Materials</h1>
                        <p class="page-subtitle mb-0">
                            Manage learning resources, videos and PDFs for this topic
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="<?= base_url('admin/topics/'.$topic['subject_id']) ?>" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i> Back
                        </a>
                        <a href="<?= base_url('admin/add_material/'.$topic['topic_id']) ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i> Add Material
                        </a>
                    </div>
                </div>

                <div class="info-card mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="admin-avatar">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <?= esc($topic['topic_name']) ?>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <div class="course-header-line mb-4">
                        <span class="course-header-title">Materials</span>
                        <span class="card-count-pill"><?= count($materials) ?> Records</span>
                        <div class="course-header-divider"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table student-table align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Material</th>
                                    <th>View</th>
                                    <th width="200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($materials)): ?>
                                    <?php foreach($materials as $material): ?>
                                        <tr>
                                            <td>
                                                <span class="id-pill"><?= esc($material['material_id']) ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <?php if($material['material_type'] == 'pdf'): ?>
                                                        <div class="student-mini-avatar bg-danger text-white">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </div>
                                                    <?php elseif($material['material_type'] == 'video'): ?>
                                                        <div class="student-mini-avatar bg-success text-white">
                                                            <i class="fas fa-video"></i>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="student-mini-avatar bg-primary text-white">
                                                            <i class="fab fa-youtube"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <strong><?= esc($material['material_title']) ?></strong>
                                                </div>
                                            </td>
                                           <td>
                                                <?php if($material['material_type'] == 'youtube'): ?>

                                                    <a href="<?= esc($material['youtube_url']) ?>"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-success rounded-pill">
                                                        <i class="fab fa-youtube"></i> Play
                                                    </a>

                                                <?php elseif($material['material_type'] == 'video'): ?>

                                                    <a href="<?= base_url($material['file_path']) ?>"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-success rounded-pill">
                                                        <i class="fas fa-play"></i> Play
                                                    </a>

                                                <?php elseif($material['material_type'] == 'pdf'): ?>

                                                    <a href="<?= base_url($material['file_path']) ?>"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-success rounded-pill">
                                                        <i class="fas fa-eye"></i> Open
                                                    </a>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="<?= base_url('admin/edit_material/'.$material['material_id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                                        <i class="fas fa-pen"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/delete_material/'.$material['material_id']) ?>" onclick="return confirm('Delete this material?')" class="btn btn-sm btn-outline-danger rounded-pill">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-photo-film fa-3x text-muted mb-3"></i>
                                            <h5>No Materials Found</h5>
                                            <p class="text-muted mb-3">Start by adding a PDF, MP4 video or YouTube material.</p>
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
    <script src="/js/admin.js"></script>
</body>
</html>