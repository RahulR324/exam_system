<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>

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

    <div class="main-content">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">

            <div>
                <h1 class="page-title mb-1">Edit Student</h1>
                <p class="page-subtitle mb-0">
                    Update student account information and credentials
                </p>
            </div>

            <a href="<?= base_url('admin/view_students') ?>" class="btn btn-outline-secondary rounded-pill">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>

        <div class="row justify-content-center">

            <div class="col-xl-8 col-lg-10">

                <div class="info-card">

                    <!-- Card Header -->
                    <div class="course-header-line mb-4">

                        <span class="course-header-title">
                            Student Information
                        </span>

                    </div>

                    <form method="post"
                          action="<?= base_url('admin/edit_student/'.$student['student_id']) ?>">

                        <?= csrf_field() ?>

                        <div class="row">

                            <!-- Student Name -->
                            <div class="col-md-6 mb-4">

                                <label class="custom-label">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Student Name
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control custom-input"
                                       value="<?= esc($student['name']) ?>"
                                       required>

                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-4">

                                <label class="custom-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    Email Address
                                </label>

                                <input type="email"
                                       name="email"
                                       class="form-control custom-input"
                                       value="<?= esc($student['email']) ?>"
                                       required>

                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-4">

                                <label class="custom-label">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    Phone Number
                                </label>

                                <input type="text"
                                       name="phone"
                                       class="form-control custom-input"
                                       value="<?= esc($student['phone']) ?>"
                                       required>

                            </div>

                            <!-- Student ID -->
                            <div class="col-md-6 mb-4">

                                <label class="custom-label">
                                    <i class="fas fa-id-card me-2 text-primary"></i>
                                    Student ID
                                </label>

                                <input type="text"
                                       class="form-control custom-input"
                                       value="#<?= esc($student['student_id']) ?>"
                                       readonly>

                            </div>

                        </div>          

                        <!-- Password -->
                        <div class="mb-4">

                            <label class="custom-label">
                                <i class="fas fa-lock me-2 text-primary"></i>
                                New Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control custom-input"
                                   placeholder="Leave blank to keep existing password">

                            <small class="text-muted mt-2 d-block">
                                If left empty, the current password will remain unchanged.
                            </small>

                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3 mt-4">

                            <button type="submit"
                                    class="btn btn-outline-primary rounded-pill px-4 py-2">

                                <i class="fas fa-save me-2"></i>
                                Update Student

                            </button>

                            <a href="<?= base_url('admin/view_students') ?>"
                               class="btn btn-light px-4 py-2">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
    <script src="/js/admin.js"></script>

</body>
</html>