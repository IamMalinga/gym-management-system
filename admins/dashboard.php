<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM admins WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2 class="text-center">Welcome, <?php echo $row['name']; ?>!</h2>
    <p class="text-center">This is your dashboard where you can manage trainers, members, workplans, payments, machines, and generate reports.</p>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Trainers</h5>
                    <p class="card-text">View and manage gym trainers.</p>
                    <a href="manage_trainers.php" class="btn btn-primary">Manage Trainers</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Members</h5>
                    <p class="card-text">View and manage gym members.</p>
                    <a href="manage_members.php" class="btn btn-primary">Manage Members</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Workplans</h5>
                    <p class="card-text">Create and update workplans for members.</p>
                    <a href="manage_workplans.php" class="btn btn-primary">Manage Workplans</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Payments</h5>
                    <p class="card-text">View and manage payment records.</p>
                    <a href="manage_payments.php" class="btn btn-primary">Manage Payments</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Machines</h5>
                    <p class="card-text">View and manage gym machines.</p>
                    <a href="manage_machines.php" class="btn btn-primary">Manage Machines</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Generate Reports</h5>
                    <p class="card-text">Generate various reports for analysis.</p>
                    <a href="generate_reports.php" class="btn btn-primary">Generate Reports</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">View Messages</h5>
                    <p class="card-text">View and reply to contact messages.</p>
                    <a href="admin_view_messages.php" class="btn btn-primary">View Messages</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Blogs</h5>
                    <p class="card-text">Create, update, and delete blog posts.</p>
                    <a href="admin_manage_blogs.php" class="btn btn-primary">Manage Blogs</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Pricing Plans</h5>
                    <p class="card-text">Create, update, and delete pricing plans.</p>
                    <a href="admin_manage_plans.php" class="btn btn-primary">Manage Pricing Plans</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Courses</h5>
                    <p class="card-text">Create, update, and delete courses under pricing plans.</p>
                    <a href="admin_manage_courses.php" class="btn btn-primary">Manage Courses</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Gallery</h5>
                    <p class="card-text">Create, update, and delete gallery images.</p>
                    <a href="admin_manage_gallery.php" class="btn btn-primary">Manage Gallery</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Log out of your account safely.</p>
                    <button type="button" class="btn btn-danger" onclick="confirmLogout()">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <form method="post" action="logout.php" id="logoutForm">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        $('#logoutModal').modal('show');
    }
</script>

<?php include('../includes/footer.php'); ?>
