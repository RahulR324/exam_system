<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add Question Bank - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
</head>

<body class="layout-fixed sidebar-expand-lg">

<div class="app-wrapper">

    <!-- NAVBAR -->
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

    <!-- SIDEBAR -->
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

    <!-- MAIN CONTENT -->
    <main class="app-main">

    <div class="main-content container-fluid p-4">

        <!-- PAGE HEADER -->
        <div class="category-header mb-4">
            <div>
                <h2 class="fw-bold mb-1">Add Question Bank</h2>
                <p class="text-muted mb-0">
                    Create a new node in the Question Bank hierarchy
                </p>
            </div>

            <a href="<?= base_url('admin/question_banks') ?>"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>
                Back
            </a>
        </div>

        <!-- SUCCESS MESSAGE -->
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ERROR MESSAGE -->
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- FORM CARD -->
        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="info-card">

                    <!-- CARD TITLE -->
                    <div class="d-flex align-items-center mb-4">

                        <div>
                            <h5 class="fw-bold mb-1">
                                Question Bank Details
                            </h5>
                        </div>

                    </div>

                    <form method="post"
                          action="<?= base_url('admin/add_question_bank') ?>">

                        <!-- Parent Question Bank -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Parent Question Bank
                            </label>

                            <select name="parent_id"
                                    class="form-control custom-input">

                                <option value="">
                                    -- Root Level --
                                </option>

                                <?php foreach($questionBanks as $bank): ?>
                                    <option value="<?= $bank['questionbank_id'] ?>">
                                        <?= esc($bank['questionbank_name']) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <small class="text-muted mt-2 d-block">
                                Leave as Root Level if this is a top-level category.
                            </small>

                        </div>

                        <!-- Question Bank Name -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Question Bank Name
                            </label>

                            <input type="text"
                                   name="questionbank_name"
                                   class="form-control custom-input"
                                   placeholder="Enter Question Bank Name"
                                   required>

                        </div>

                        <!-- Description -->
                        <div class="mb-4">

                            <label class="form-label custom-label">
                                Description
                            </label>

                            <textarea name="description"
                                      class="form-control custom-input"
                                      rows="5"
                                      placeholder="Enter Description"></textarea>

                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="d-flex justify-content-end gap-2">

                            <a href="<?= base_url('admin/question_banks') ?>"
                               class="btn btn-light rounded-pill px-4">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-outline-primary rounded-pill px-4">

                                <i class="ti ti-device-floppy me-2"></i>
                                Save Question Bank

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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
<script src="/js/admin.js"></script>

</body>
</html>