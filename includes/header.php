<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management System</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .nav-link {
            margin-right: 1rem;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #f0ad4e;
        }
        .navbar-nav .nav-item:last-child .nav-link {
            margin-right: 0;
        }
        .navbar-dark .navbar-toggler-icon {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .navbar-dark .navbar-toggler-icon:after {
            content: '\f0c9';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: #fff;
        }
        .container.mt-4 {
            padding-top: 4rem;
        }
        .container-fluid {
            padding: 0 2rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../assets/image/logo.jpg"><i class="fas fa-dumbbell"></i> Gym Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../public/index.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/about.php"><i class="fas fa-info-circle"></i> About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/courses.php"><i class="fas fa-dumbbell"></i> Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/pricing.php"><i class="fas fa-tags"></i> Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/gallery.php"><i class="fas fa-images"></i> Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/blog.php"><i class="fas fa-blog"></i> Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../public/contact.php"><i class="fas fa-envelope"></i> Contact</a>
                </li>
                <?php
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['username'])) {
                    if ($_SESSION['role'] == 'admin') {
                        echo '<li class="nav-item"><a class="nav-link" href="../admins/dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>';
                    } elseif ($_SESSION['role'] == 'trainer') {
                        echo '<li class="nav-item"><a class="nav-link" href="../trainers/dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>';
                    } elseif ($_SESSION['role'] == 'member') {
                        echo '<li class="nav-item"><a class="nav-link" href="../members/dashboard.php"><i class="fas fa-user"></i> Dashboard</a></li>';
                    }
                    echo '<li class="nav-item"><a class="nav-link" href="../members/logout.php" onclick="confirmLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>';
                } else {
                    echo '<li class="nav-item"><a class="nav-link" href="../public/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>';
                    echo '<li class="nav-item"><a class="nav-link" href="../public/register.php"><i class="fas fa-user-plus"></i> Register</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <!-- Page Content -->
</div>
<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "../members/logout.php";
    }
}
</script>

</body>
</html>
