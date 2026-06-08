<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css">
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
            <div class="main-content">

                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <h1 class="page-title mb-1">Students</h1>
                        <p class="page-subtitle mb-0">Manage all registered system students</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('admin/view_student_courses') ?>" class="btn btn-outline-success rounded-pill px-4">
                            <i class="fas fa-clipboard-user me-2"></i> Assigned Courses
                        </a>
                        <a href="<?= base_url('admin/add_student') ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-plus me-2"></i> Add Student
                        </a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-1 fw-bold">Student Directory</h5>
                            </div>
                            <span class="card-count-pill"><?= count($students) ?> Students</span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table student-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Student</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th width="220">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($students)): ?>
                                        <?php foreach($students as $student): ?>
                                            <tr>
                                                <td><span class="id-pill"><?= esc($student['student_id']) ?></span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="student-mini-avatar"><?= strtoupper(substr($student['name'], 0, 1)) ?></div>
                                                        <div><strong><?= esc($student['name']) ?></strong></div>
                                                    </div>
                                                </td>
                                                <td><?= esc($student['email']) ?></td>
                                                <td><?= esc($student['phone'] ?? '-') ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="<?= base_url('admin/edit_student/'.$student['student_id']) ?>" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                            <i class="fas fa-pen me-1"></i> Edit
                                                        </a>
                                                        <a href="<?= base_url('admin/delete_student/'.$student['student_id']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="return confirm('Are you sure you want to delete this student?')">
                                                            <i class="fas fa-trash me-1"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="text-center py-5">
                                                    <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                                                    <h5>No Students Found</h5>
                                                    <p class="text-muted mb-0">No registered students are available.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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