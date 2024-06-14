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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $milestone = $_POST['milestone'];

    $stmt = $conn->prepare("INSERT INTO milestones (member_id, milestone) VALUES (?, ?)");
    $stmt->bind_param("is", $member_id, $milestone);
    
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Milestone assigned successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$member_sql = "SELECT * FROM members";
$member_result = $conn->query($member_sql);
?>

<div class="container mt-5">
    <h2>Assign Milestone</h2>
    <?php if (isset($message)) echo $message; ?>
    <form method="post" action="" class="mb-4">
        <div class="form-group">
            <label for="member_id">Select Member:</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <?php while($row = $member_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="milestone">Milestone:</label>
            <input type="text" id="milestone" name="milestone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Assign Milestone</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
