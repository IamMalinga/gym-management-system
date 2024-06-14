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
$sql = "SELECT id, name FROM members WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$member_id = $row['id'];
$member_name = $row['name'];

$progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
$progress_result = $conn->query($progress_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Progress Report for <?php echo htmlspecialchars($member_name); ?></h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Weight (kg)</th>
                    <th>Height (cm)</th>
                    <th>BMI</th>
                    <th>Body Fat (%)</th>
                    <th>Muscle Mass (kg)</th>
                    <th>Goal Weight (kg)</th>
                    <th>Goal Body Fat (%)</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $progress_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['weight']); ?></td>
                        <td><?php echo htmlspecialchars($row['height']); ?></td>
                        <td><?php echo htmlspecialchars($row['bmi']); ?></td>
                        <td><?php echo htmlspecialchars($row['body_fat_percentage']); ?></td>
                        <td><?php echo htmlspecialchars($row['muscle_mass']); ?></td>
                        <td><?php echo htmlspecialchars($row['goal_weight']); ?></td>
                        <td><?php echo htmlspecialchars($row['goal_body_fat_percentage']); ?></td>
                        <td><?php echo htmlspecialchars($row['notes']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <p><a href="generate_report.php" target="_blank" class="btn btn-primary mt-3">Download Progress Report</a></p>
</div>

<?php include('../includes/footer.php'); ?>
