<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results - Admin Panel</title>

    <!-- Global CSS -->
    <link rel="stylesheet" href="/css/adminstyles.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Overlay Scrollbars -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"/>

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open">

<div class="app-wrapper">

    <!-- NAVBAR -->

    <nav class="app-header navbar navbar-expand bg-body">

        <div class="container-fluid">

            <ul class="navbar-nav">

                <li class="nav-item">

                    <a class="nav-link"
                       data-lte-toggle="sidebar"
                       href="#">

                        <i class="fas fa-bars"></i>

                    </a>

                </li>

                <li class="nav-item">

                    <a href="/admin/dashboard"
                       class="nav-link">

                        <i class="fa-solid fa-house"></i>
                        Dashboard

                    </a>

                </li>

            </ul>

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">

                    <a href="/admin/logout"
                       class="btn btn-danger btn-sm">

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </nav>

    <!-- SIDEBAR -->

    <aside class="app-sidebar bg-dark shadow"
           data-bs-theme="dark">

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

                        <a href="/admin/view-all-exams"
                           class="nav-link active">

                            <i class="nav-icon fas fa-clipboard-list"></i>

                            <p>Exams</p>

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

    <!-- MAIN -->

    <main class="app-main">

        <div class="main-content">

            <!-- PAGE HEADER -->

            <div class="page-title">

                <h1>Exam Results</h1>

                <p>
                    Comprehensive student performance and participation analytics
                </p>

            </div>

            <!-- RESULTS CARD -->

            <div class="card custom-card">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>

                            <h3 class="card-title mb-1">
                                Student Exam Performance
                            </h3>

                            <?php if (!empty($exam_title)): ?>

                                <small class="text-muted" style="margin-left: 30px; font-size: 1rem;">

                                    Exam:
                                    <strong><?= esc($exam_title) ?></strong>

                                </small>

                            <?php endif; ?>

                        </div>

                        <a href="/admin/view-all-exams"
                           class="btn btn-secondary btn-sm">

                            <i class="bi bi-arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>

                <div class="card-body">

                    <?php if (empty($students)): ?>

                        <!-- EMPTY STATE -->

                        <div class="empty-state text-center py-5">

                            <i class="fas fa-chart-line fs-1 text-muted mb-3"></i>

                            <h4 class="fw-bold">
                                No Results Available
                            </h4>

                            <p class="text-muted">
                                No students have attended this exam yet.
                            </p>

                        </div>

                    <?php else: ?>

                        <!-- TABLE -->

                        <div class="table-responsive">

                            <table class="table align-middle custom-table">

                                <thead>

                                    <tr>

                                        <th>Reg Number</th>

                                        <th>Student Name</th>

                                        <th>Exam Title</th>

                                        <th>Answered</th>

                                        <th>Raw Score</th>

                                        <th>Percentage</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php foreach($students as $student): ?>

                                    <tr>

                                        <td>

                                            <strong>
                                                <?= esc($student['register_number']) ?>
                                            </strong>

                                        </td>

                                        <td>

                                            <?= esc($student['name']) ?>

                                        </td>

                                        <td>

                                            <?= esc($student['exam_title'] ?? 'N/A') ?>

                                        </td>

                                        <td>

                                            <?= esc($student['answered_count']) ?>

                                            /

                                            <?= esc($student['total_questions']) ?>

                                        </td>

                                        <td>

                                            <strong>

                                                <?= esc($student['score']) ?>

                                                /

                                                <?= esc($student['total_questions']) ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?php if ($student['submitted'] && $student['total_questions'] > 0): ?>

                                                <span class="text-primary fw-bold">

                                                    <?= round(($student['score'] / $student['total_questions']) * 100, 1) ?>%

                                                </span>

                                            <?php else: ?>

                                                <span class="text-muted">

                                                    N/A

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                        <td>

                                            <?php if ($student['submitted']): ?>

                                                <span class="badge bg-success">

                                                    Submitted

                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-danger">

                                                    Not Attended

                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </main>

</div>

<!-- Scripts -->

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>

</body>
</html>