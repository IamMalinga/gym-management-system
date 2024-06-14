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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $milestone_name = $_POST['milestone'];
    $milestone_date = $_POST['milestone_date'];

    // Insert milestone data
    $stmt = $conn->prepare("INSERT INTO milestones (member_id, milestone_name, achieved_date, date) VALUES (?, ?, NULL, ?)");
    $stmt->bind_param("iss", $member_id, $milestone_name, $milestone_date);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Milestone added successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-5">
    <h2>Add Milestone</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="post" action="" class="mb-4">
        <div class="form-group">
            <label for="milestone_date">Date:</label>
            <input type="date" id="milestone_date" name="milestone_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="milestone">Milestone:</label>
            <textarea id="milestone" name="milestone" class="form-control" required></textarea>
        </div>
        <button type="submit" name="add_milestone" class="btn btn-primary">Add Milestone</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
