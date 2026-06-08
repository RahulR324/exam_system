<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Exam System</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="/css/loginstyle.css">

</head>

<body>

<div class="login-wrapper">

    <!-- LEFT SIDE -->

    <div class="login-left">

        <div class="overlay"></div>

        <div class="left-content">
            <h1>Exam Administration Panel</h1>

            <p>
                Securely manage examinations, students,
                results, and question banks from one centralized dashboard.
            </p>

        </div>

    </div>

    <!-- RIGHT SIDE -->

    <div class="login-right">

        <div class="login-card">

            <div class="login-header">

                <h2>Administrator Login</h2>

                <p>
                    Sign in to continue to the admin dashboard
                </p>

            </div>

            <?php if(session()->getFlashdata('error')): ?>

                <div class="alert-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        <?= esc(session()->getFlashdata('error')) ?>
                    </span>

                </div>

            <?php endif; ?>

            <form method="post"
                  action="/admin/login">

                <?= csrf_field(); ?>

                <!-- USERNAME -->

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <div class="input-group">

                        <i class="fa-solid fa-user"></i>

                        <input type="text"
                               id="username"
                               name="username"
                               placeholder="Enter admin username"
                               required>

                    </div>

                </div>

                <!-- PASSWORD -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="input-group">

                        <i class="fa-solid fa-lock"></i>

                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter password"
                               required>

                    </div>

                </div>

                <!-- BUTTON -->

                <button type="submit"
                        class="login-btn">

                    <i class="fa-solid fa-right-to-bracket"></i>

                    Authenticate

                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>