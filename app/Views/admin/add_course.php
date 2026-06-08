<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add Course Category - Admin</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"/>
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

    <div class="main-content container-fluid p-4">

        <!-- Page Header -->
        <div class="category-header mb-4">

            <div>
                <h2 class="fw-bold mb-1">
                    Add Course
                </h2>

                <p class="text-muted mb-0">
                    Create and publish a new course for students
                </p>
            </div>

            <a href="/admin/courses"
               class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>
                Back
            </a>

        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">

            <div class="col-xl-10">

                <div class="info-card">

                    <!-- Card Header -->
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>
                            <h4 class="fw-bold mb-1">
                                Course Information
                            </h4>
                        </div>

                    </div>

                    <form method="post"
                          enctype="multipart/form-data"
                          action="<?= base_url('admin/add_course') ?>">

                        <div class="row">

                            <!-- Category -->
                            <div class="col-md-6 mb-4">

                                <label class="form-label custom-label">
                                    Category
                                </label>

                                <select name="category_id"
                                        class="form-control custom-input"
                                        required>

                                    <option value="">
                                        Select Category
                                    </option>

                                    <?php foreach($categories as $category): ?>
                                        <option value="<?= $category['category_id'] ?>">
                                            <?= esc($category['category_name']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <!-- Price -->
                            <div class="col-md-6 mb-4">

                                <label class="form-label custom-label">
                                    Course Price
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="price"
                                           class="form-control custom-input"
                                           placeholder="0.00"
                                           required>

                                </div>

                            </div>

                            <!-- Course Name -->
                            <div class="col-12 mb-4">

                                <label class="form-label custom-label">
                                    Course Name
                                </label>

                                <input type="text"
                                       name="course_name"
                                       class="form-control custom-input"
                                       placeholder="Enter course name"
                                       required>

                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-4">

                                <label class="form-label custom-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="5"
                                          class="form-control custom-input"
                                          placeholder="Provide a brief overview of the course contents..."></textarea>

                            </div>

                            <!-- Thumbnail -->
                            <div class="col-12 mb-4">

                                <label class="form-label custom-label">
                                    Course Thumbnail
                                </label>

                                <div class="upload-box">

                                    <i class="fas fa-image upload-icon"></i>

                                    <input type="file"
                                           name="thumbnail"
                                           id="thumbnail"
                                           class="form-control custom-input"
                                           accept="image/*">

                                    <small class="text-muted mt-2 d-block">
                                        JPG, PNG or WEBP image recommended
                                    </small>

                                </div>

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a href="/admin/courses"
                               class="btn btn-light rounded-pill px-4">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-outline-primary rounded-pill px-4">

                                <i class="ti ti-device-floppy me-2"></i>
                                Save Course

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
    <script src="/js/admin.js"></script>
    <script>
        document.getElementById('thumbnail').addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/jpg',
                'image/gif'
            ];

            if (!allowedTypes.includes(file.type)) {

                alert('Please upload a valid image file (JPG, JPEG, PNG, WEBP, GIF).');

                this.value = ''; // Clear selected file
            }
        });
    </script>

</body>
</html>