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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $requirements = $_POST['requirements'];
    $created_by = $_SESSION['username'];

    $sql = "INSERT INTO workplans (name, description, requirements, created_by) 
            VALUES ('$name', '$description', '$requirements', (SELECT id FROM admins WHERE email='$created_by'))";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Workplan created successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$workplans_sql = "SELECT * FROM workplans";
$workplans_result = $conn->query($workplans_sql);
?>

<div class="container mt-5">
    <h2>Manage Workplans</h2>

    <h3>Create New Workplan</h3>
    <form method="post" action="" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea id="description" name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="requirements" class="form-label">Requirements:</label>
            <textarea id="requirements" name="requirements" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Workplan</button>
    </form>

    <h3>Existing Workplans</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Requirements</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $workplans_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['description']; ?></td>
                        <td><?php echo $row['requirements']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
