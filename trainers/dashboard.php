<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'trainer') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM trainers WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Welcome, <?php echo htmlspecialchars($row['name']); ?>!</h2>
    <p class="text-center">This is your dashboard where you can manage members, workplans, and update progress.</p>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Members</h5>
                    <p class="card-text">View and manage gym members.</p>
                    <a href="manage_members.php" class="btn btn-primary">Manage Members</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Manage Workplans</h5>
                    <p class="card-text">Create and update workplans for members.</p>
                    <a href="manage_workplans.php" class="btn btn-primary">Manage Workplans</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Update Progress</h5>
                    <p class="card-text">Update and track member progress.</p>
                    <a href="update_progress.php" class="btn btn-primary">Update Progress</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">View Member Progress</h5>
                    <p class="card-text">Visualize and review member progress.</p>
                    <a href="view_member_progress.php" class="btn btn-primary">View Member Progress</a>
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
        
        <!-- New Card for Visualizing Member Progress -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Visualize Member Progress</h5>
                    <p class="card-text">Visualize the progress of gym members.</p>
                    <a href="visualize_progress.php" class="btn btn-primary">Visualize Progress</a>
                </div>
            </div>
        </div>

                <!-- New Section for Giving Approvals -->
                <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Give Approvals</h5>
                    <p class="card-text">Approve members' applied workplans.</p>
                    <a href="give_approvals.php" class="btn btn-primary">Give Approvals</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
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
