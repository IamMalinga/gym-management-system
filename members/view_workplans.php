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
$sql = "SELECT id FROM members WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$member_id = $row['id'];

$workplan_sql = "SELECT * FROM courses";
$workplan_result = $conn->query($workplan_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">View Workplans</h2>
    <div class="row">
        <?php while($row = $workplan_result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($row['name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <!-- Uncomment the following line to include an image -->
                        <!-- <img src="path_to_image.jpg" class="card-img-top mb-2" alt="Image description"> -->
                        <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Requirements:</strong> <?php echo htmlspecialchars($row['requirements']); ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-outline-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
