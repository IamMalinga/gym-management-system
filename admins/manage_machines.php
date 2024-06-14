<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machine_name = $_POST['machine_name'];
    $last_service_date = $_POST['last_service_date'];
    $next_service_date = $_POST['next_service_date'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO machine_maintenance (machine_name, last_service_date, next_service_date, notes) VALUES ('$machine_name', '$last_service_date', '$next_service_date', '$notes')";
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">Machine maintenance record added successfully</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $sql . '<br>' . $conn->error . '</div>';
    }
}

$machines_sql = "SELECT * FROM machine_maintenance";
$machines_result = $conn->query($machines_sql);
?>

<div class="container mt-5">
    <h2>Manage Machines</h2>
    
    <?= $message; ?>

    <h3>Add Maintenance Record</h3>
    <form method="post" action="" class="mb-5">
        <div class="form-group">
            <label for="machine_name">Machine Name:</label>
            <input type="text" class="form-control" id="machine_name" name="machine_name" required>
        </div>
        <div class="form-group">
            <label for="last_service_date">Last Service Date:</label>
            <input type="date" class="form-control" id="last_service_date" name="last_service_date" required>
        </div>
        <div class="form-group">
            <label for="next_service_date">Next Service Date:</label>
            <input type="date" class="form-control" id="next_service_date" name="next_service_date" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea class="form-control" id="notes" name="notes"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Record</button>
    </form>

    <h3>Existing Machines</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Machine Name</th>
                <th>Last Service Date</th>
                <th>Next Service Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $machines_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['machine_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['last_service_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['next_service_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['notes']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>
