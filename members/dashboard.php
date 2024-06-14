<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>
<?php
   if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM members WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2 class="text-center">Welcome, <?php echo htmlspecialchars($row['name']); ?>!</h2>
    <p class="text-center">This is your dashboard where you can manage your profile, track progress, view workplans, and more.</p>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Update Profile</h5>
                    <p class="card-text">Edit your personal information and update your profile.</p>
                    <a href="update_profile.php" class="btn btn-primary">Go to Update Profile</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">View Workplans</h5>
                    <p class="card-text">Check your current and past workplans.</p>
                    <a href="view_workplans.php" class="btn btn-primary">View Workplans</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Apply to Workplan</h5>
                    <p class="card-text">Enroll in new workplans tailored to your fitness goals.</p>
                    <a href="apply_workplan.php" class="btn btn-primary">Apply to Workplan</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Track Progress</h5>
                    <p class="card-text">Monitor your progress and achievements.</p>
                    <a href="progress.php" class="btn btn-primary">Track Progress</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Attendance</h5>
                    <p class="card-text">View your attendance records and logs.</p>
                    <a href="attendance.php" class="btn btn-primary">View Attendance</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Payments</h5>
                    <p class="card-text">Check your payment history and make new payments.</p>
                    <a href="payments.php" class="btn btn-primary">Manage Payments</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Notifications</h5>
                    <p class="card-text">Stay updated with the latest notifications.</p>
                    <a href="notifications.php" class="btn btn-primary">View Notifications</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Milestones</h5>
                    <p class="card-text">View your achieved milestones and rewards.</p>
                    <a href="milestones.php" class="btn btn-primary">View Milestones</a>
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
                <form method="post" action="logout.php">
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
