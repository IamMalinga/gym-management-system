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

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $workplan_id = $_POST['workplan_id'];

    $apply_sql = "INSERT INTO registrations (course_workout_id, user_id) VALUES ('$workplan_id', '$member_id')";
    if ($conn->query($apply_sql) === TRUE) {
        $message = "Applied to workplan successfully.";
    } else {
        $error = "Error: " . $apply_sql . "<br>" . $conn->error;
    }
}

$workplan_sql = "SELECT * FROM courses";
$workplan_result = $conn->query($workplan_sql);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="card-title mb-0">Apply to Workplan</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="workplan_id" class="form-label">Select Workplan:</label>
                            <select id="workplan_id" name="workplan_id" class="form-select" required>
                                <?php while($row = $workplan_result->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<style>
    .card:hover {
        transform: scale(1.02);
        transition: transform 0.2s;
    }

    .card{
        margin-bottom: 20px;
    }
</style>
