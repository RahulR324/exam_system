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
            <div class="main-content container-fluid p-4">
                <div class="category-header mb-4">
                    <div>
                        <h2 class="fw-bold mb-1">Edit Material</h2>
                        <p class="text-muted mb-0">Update study material details</p>
                    </div>

                    <a href="<?= base_url('admin/topic_materials/'.$topic['topic_id']) ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>

                <div class="course-header-line mb-4">
                    <h5 class="course-header-title mb-0"><?= esc($topic['topic_name']) ?></h5>
                    <span class="course-header-pill">Topic</span>
                    <div class="course-header-divider"></div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="info-card">
                            <div class="mb-4">
                                <h5 class="fw-bold mb-1">Material Details</h5>
                            </div>

                            <form action="<?= base_url('admin/edit_material/'.$material['material_id']) ?>" method="post" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label class="form-label custom-label">Material Title</label>
                                    <input type="text" name="material_title" class="form-control custom-input" value="<?= esc($material['material_title']) ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label custom-label">Description</label>
                                    <textarea name="description" rows="5" class="form-control custom-input"><?= esc($material['description'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label custom-label">Material Type</label>
                                    <select name="material_type" id="materialType" class="form-control custom-input" onchange="toggleMaterialFields()" required>
                                        <option value="pdf" <?= $material['material_type'] == 'pdf' ? 'selected' : '' ?>>PDF Document</option>
                                        <option value="video" <?= $material['material_type'] == 'video' ? 'selected' : '' ?>>MP4 Video</option>
                                        <option value="youtube" <?= $material['material_type'] == 'youtube' ? 'selected' : '' ?>>YouTube Video</option>
                                    </select>
                                </div>

                                <?php if(!empty($material['file_path'])): ?>
                                    <div class="alert alert-info mb-4">
                                        <i class="fas fa-file me-2"></i>
                                        Current File: <strong><?= basename($material['file_path']) ?></strong>
                                    </div>
                                <?php endif; ?>

                                <div id="youtubeSection">
                                    <div class="mb-4">
                                        <label class="form-label custom-label">YouTube URL</label>
                                        <input type="url" name="youtube_url" class="form-control custom-input" value="<?= esc($material['youtube_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=xxxxx">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('admin/topic_materials/'.$topic['topic_id']) ?>" class="btn btn-light rounded-pill px-4">Cancel</a>
                                    <button type="submit" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i> Update Material
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/js/adminlte.min.js"></script>
    <script src="/js/admin.js"></script>
    <script>
        function toggleMaterialFields() {
            let type = document.getElementById('materialType').value;
            let fileSection = document.getElementById('fileSection');
            let youtubeSection = document.getElementById('youtubeSection');

            if(type === 'youtube') {
                youtubeSection.style.display = 'block';
                fileSection.style.display = 'none';
            } else {
                youtubeSection.style.display = 'none';
                fileSection.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleMaterialFields();
        });
    </script>
</body>
</html>