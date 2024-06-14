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
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    $stmt = $conn->prepare("INSERT INTO attendance (member_id, check_in, check_out) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $member_id, $check_in, $check_out);

    if ($stmt->execute()) {
        $message = "Attendance marked successfully.";
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$attendance_sql = "SELECT * FROM attendance WHERE member_id='$member_id' ORDER BY created_at DESC";
$attendance_result = $conn->query($attendance_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Mark Attendance</h2>
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
    <form method="post" action="" class="card p-4 shadow-sm mb-4">
        <div class="mb-3">
            <label for="check_in" class="form-label">Check-In:</label>
            <input type="datetime-local" id="check_in" name="check_in" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="check_out" class="form-label">Check-Out:</label>
            <input type="datetime-local" id="check_out" name="check_out" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-block">Mark Attendance</button>
        </div>
    </form>

    <h3 class="text-center mb-4">Attendance History</h3>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Recorded At</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $attendance_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['check_in']); ?></td>
                        <td><?php echo htmlspecialchars($row['check_out']); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
