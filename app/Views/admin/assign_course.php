<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Course</title>

    <link rel="stylesheet" href="/css/adminstyle.css">
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
        <div class="main-content">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h1 class="page-title mb-1">Assign Course</h1>
                    <p class="page-subtitle mb-0">Assign a course to a student and set the completion target date</p>
                </div>
                <a href="<?= base_url('admin/view_student_courses') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-1 fw-bold">Course Assignment Details</h5>
                        </div>
                        <div class="card-body p-4">
                            <form method="post" action="<?= base_url('admin/assign_course') ?>">
                                <?= csrf_field() ?>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Student</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-graduate text-primary"></i></span>
                                        <select name="student_id" class="form-control custom-input" required>
                                            <option value="">Select Student</option>
                                            <?php foreach($students as $student): ?>
                                                <option value="<?= $student['student_id'] ?>"><?= esc($student['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Course</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-book-open text-primary"></i></span>
                                        <select name="course_id" class="form-control custom-input" required>
                                            <option value="">Select Course</option>
                                            <?php foreach($courses as $course): ?>
                                                <option value="<?= $course['course_id'] ?>"><?= esc($course['course_name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Assigned Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar-alt text-primary"></i></span>
                                        <input type="date" name="assigned_date" class="form-control custom-input" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Completion Target Date</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-flag-checkered text-primary"></i></span>
                                        <input type="date" name="completion_date" class="form-control custom-input">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i> Assign Course
                                    </button>
                                    <a href="<?= base_url('admin/view_student_courses') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="fas fa-times me-2"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
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