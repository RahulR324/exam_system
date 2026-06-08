<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"/>
</head>

<body class="layout-fixed sidebar-expand-lg">

<div class="app-wrapper">

    <!-- ================= NAVBAR ================= -->
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

    <!-- ================= SIDEBAR ================= -->
    <aside class="app-sidebar">

        <!-- BRAND -->

        <div class="sidebar-brand">

            <a href="/admin/dashboard"
               class="brand-link text-decoration-none">

                <span class="brand-text fw-light">
                    EXAM ADMIN
                </span>

            </a>

        </div>

        <!-- SIDEBAR MENU -->

        <div class="sidebar-wrapper">

            <nav class="mt-2">

                <ul class="nav sidebar-menu flex-column"
                    data-lte-toggle="treeview"
                    role="menu">

                    <li class="nav-item">

                        <a href="/admin/dashboard"
                           class="nav-link">

                            <i class="nav-icon fas fa-gauge-high"></i>

                            <p>Dashboard</p>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="/admin/course_categories"
                           class="nav-link">

                            <i class="nav-icon fas fa-layer-group"></i>

                            <p>Course Categories</p>

                        </a>
                    </li>

                    <li class="nav-item">

                        <a href="/admin/courses"
                           class="nav-link">
                            <i class="nav-icon fas fa-book-open"></i>
                            <p>Courses</p>
                        </a>
                    </li>

                    <li class="nav-item">

                        <a href="/admin/exams"
                           class="nav-link">

                            <i class="nav-icon fas fa-clipboard-list"></i>

                            <p>Exams</p>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="/admin/question_banks"
                           class="nav-link">

                            <i class="nav-icon fas fa-clipboard-question"></i>

                            <p>Question Bank</p>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="/admin/view_students"
                           class="nav-link">

                            <i class="nav-icon fas fa-users"></i>

                            <p>Students</p>

                        </a>

                    </li>

                </ul>

            </nav>

        </div>

    </aside>

    <!-- ================= MAIN ================= -->
    <main class="app-main">

    <div class="main-content container-fluid p-4">

        <!-- HEADER -->
        <div class="category-header mb-4">

            <div>
                <h2 class="fw-bold mb-1">Edit Exam</h2>
                <p class="text-muted mb-0">
                    Update and manage examination details
                </p>
            </div>

            <a href="/admin/exams"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>
                Back
            </a>

        </div>

        <!-- FORM SECTION -->
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="info-card">

                    <div class="mb-4">

                        <h5 class="fw-bold mb-1">
                            Exam Details
                        </h5>

                    </div>

                    <form method="post"
                          action="<?= base_url('admin/edit_exam/'.$exam['exam_id']) ?>">

                        <!-- COURSE -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Course
                            </label>

                            <select name="course_id"
                                    class="form-select custom-input"
                                    required>

                                <option value="">
                                    Select Course
                                </option>

                                <?php foreach($courses as $course): ?>

                                    <option value="<?= $course['course_id'] ?>"
                                        <?= ($course['course_id'] == $exam['course_id']) ? 'selected' : '' ?>>

                                        <?= esc($course['course_name']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <!-- TITLE -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Exam Title
                            </label>

                            <input type="text"
                                   name="title"
                                   class="form-control custom-input"
                                   value="<?= esc($exam['title']) ?>"
                                   required>

                        </div>

                        <!-- DATE -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Exam Date
                            </label>

                            <input type="date"
                                   name="date"
                                   class="form-control custom-input"
                                   value="<?= esc($exam['date']) ?>"
                                   required>

                        </div>

                        <!-- TIME -->
                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label custom-label">
                                    Start Time
                                </label>

                                <input type="time"
                                       name="start_time"
                                       class="form-control custom-input"
                                       value="<?= esc($exam['start_time']) ?>"
                                       required>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label custom-label">
                                    End Time
                                </label>

                                <input type="time"
                                       name="end_time"
                                       class="form-control custom-input"
                                       value="<?= esc($exam['end_time']) ?>"
                                       required>

                            </div>

                        </div>

                        <!-- DURATION -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Duration (Minutes)
                            </label>

                            <input type="number"
                                   name="duration"
                                   min="1"
                                   class="form-control custom-input"
                                   value="<?= esc($exam['duration']) ?>"
                                   required>

                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="/admin/exams"
                               class="btn btn-light rounded-pill px-4">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-outline-primary rounded-pill px-4">

                                <i class="ti ti-device-floppy me-2"></i>
                                Update Exam

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
<script src="/js/admin.js"></script>

</body>
</html>