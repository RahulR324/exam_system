<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Dashboard - Exam System</title>

    <link rel="stylesheet" href="/css/adminstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"/>
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
                        <h2 class="fw-bold mb-1">Add Material</h2>
                        <p class="text-muted mb-0">
                            Upload study materials for this topic
                        </p>
                    </div>

                    <a href="<?= base_url('admin/topic_materials/'.$topic['topic_id']) ?>"
                       class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back
                    </a>
                </div>

                <div class="course-header-line mb-4">
                    <h5 class="course-header-title mb-0">
                        <?= esc($topic['topic_name']) ?>
                    </h5>
                    <span class="course-header-pill">
                        Topic
                    </span>
                    <div class="course-header-divider"></div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="info-card">
                            <div class="mb-4">
                                <h5 class="fw-bold mb-1">
                                    Material Details
                                </h5>
                            </div>

                            <form action="<?= base_url('admin/add_material/'.$topic['topic_id']) ?>"
                                  method="post"
                                  enctype="multipart/form-data">

                                <input type="hidden"
                                       name="topic_id"
                                       value="<?= $topic['topic_id'] ?>">

                                <div class="mb-4">
                                    <label class="form-label custom-label">
                                        Material Title
                                    </label>
                                    <input type="text"
                                           name="material_title"
                                           class="form-control custom-input"
                                           placeholder="Enter material title"
                                           required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label custom-label">
                                        Description
                                    </label>
                                    <textarea name="description"
                                              rows="5"
                                              class="form-control custom-input"
                                              placeholder="Brief description about this material"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label custom-label">
                                        Material Type
                                    </label>
                                    <select name="material_type"
                                            id="materialType"
                                            class="form-control custom-input"
                                            required>
                                        <option value="">Select Material Type</option>
                                        <option value="pdf">PDF Document</option>
                                        <option value="video">MP4 Video</option>
                                        <option value="youtube">YouTube Video</option>
                                    </select>
                                </div>

                                <div class="mb-4"
                                     id="youtubeSection"
                                     style="display:none;">
                                    <label class="form-label custom-label">
                                        YouTube URL
                                    </label>
                                    <input type="text"
                                           name="youtube_url"
                                           class="form-control custom-input"
                                           placeholder="https://youtube.com/watch?v=...">
                                </div>

                                <div class="mb-4" id="fileUploadSection">
                                    <label class="form-label custom-label">
                                        Upload File
                                    </label>
                                    <input type="file"
                                           id="materialFile"
                                           name="material_file"
                                           class="form-control custom-input">
                                    <small class="text-muted">
                                        Supported formats: PDF, MP4
                                    </small>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="<?= base_url('admin/topic_materials/'.$topic['topic_id']) ?>"
                                       class="btn btn-light rounded-pill px-4">
                                        Cancel
                                    </a>
                                    <button type="submit"
                                            class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="fas fa-save me-2"></i>
                                        Save Material
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
        document.addEventListener('DOMContentLoaded', function () {
            const materialType = document.getElementById('materialType');
            const youtubeSection = document.getElementById('youtubeSection');
            const fileUploadSection = document.getElementById('fileUploadSection');
            const dropZoneSection = document.getElementById('dropZoneSection');

            materialType.addEventListener('change', function () {
                if (this.value === 'youtube') {
                    youtubeSection.style.display = 'block';
                    fileUploadSection.style.display = 'none';
                    dropZoneSection.style.display = 'none';
                } else {
                    youtubeSection.style.display = 'none';
                    fileUploadSection.style.display = 'block';
                    dropZoneSection.style.display = 'block';
                }
            });

            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('materialFile');

            dropZone.addEventListener('dragover', function(e){
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', function(){
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', function(e){
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                fileInput.files = files;
            });

            dropZone.addEventListener('click', function(){
                fileInput.click();
            });
        });
    </script>
</body>
</html>