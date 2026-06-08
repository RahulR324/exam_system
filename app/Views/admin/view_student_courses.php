<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Assigned Courses - Admin</title>

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
        <div class="main-content">

            <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="page-title mb-1">Assigned Courses</h1>
                    <p class="page-subtitle mb-0">Manage student course assignments and monitor learning progress</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('admin/view_students') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                    <a href="<?= base_url('admin/assign_course') ?>" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-plus me-2"></i> Assign Course
                    </a>
                </div>
            </div>

            <div class="info-card mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-avatar"><i class="fas fa-user-graduate"></i></div>
                    <div>
                        <h5 class="mb-1 fw-bold">Course Assignment Overview</h5>
                        <small class="text-muted"><?= count($assignedCourses) ?> active student-course assignments found</small>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="course-header-line mb-4">
                    <span class="course-header-title">Assigned Courses</span>
                    <span class="card-count-pill"><?= count($assignedCourses) ?> Records</span>
                    <div class="course-header-divider"></div>
                </div>

                <div class="table-responsive">
                    <table class="table student-table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Assigned</th>
                                <th>Progress</th>
                                <th>Status</th>
                                <th>Target Date</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($assignedCourses)): ?>
                                <?php foreach($assignedCourses as $row): ?>
                                <tr>
                                    <td><span class="id-pill"><?= esc($row['student_course_id']) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="student-mini-avatar"><?= strtoupper(substr($row['student_name'],0,1)) ?></div>
                                            <strong><?= esc($row['student_name']) ?></strong>
                                        </div>
                                    </td>
                                    <td><strong><?= esc($row['course_name']) ?></strong></td>
                                    <td><?= date('d M Y', strtotime($row['assigned_date'])) ?></td>
                                    <td style="min-width:180px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height:8px;border-radius:50px;">
                                                <div class="progress-bar" style="width:<?= $row['progress'] ?>%; background:linear-gradient(135deg,#4f46e5,#6366f1);"></div>
                                            </div>
                                            <span class="fw-semibold text-primary"><?= $row['progress'] ?>%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($row['completed_status']): ?>
                                            <span class="badge rounded-pill text-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill text-secondary px-3 py-2"><i class="fas fa-clock me-1"></i> In Progress</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= !empty($row['completion_date']) ? date('d M Y', strtotime($row['completion_date'])) : '<span class="text-muted">Not Set</span>' ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('admin/edit_student_course/'.$row['student_course_id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill"><i class="fas fa-pen"></i> Edit</a>
                                            <a href="<?= base_url('admin/delete_student_course/'.$row['student_course_id']) ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete this assignment?')"><i class="fas fa-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <h5>No Assigned Courses Found</h5>
                                        <p class="text-muted mb-0">Start by assigning a course to a student.</p>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
<script src="/js/admin.js"></script>

</body>
</html>