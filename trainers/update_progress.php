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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $bmi = $weight / (($height / 100) * ($height / 100));
    $body_fat_percentage = $_POST['body_fat_percentage'];
    $muscle_mass = $_POST['muscle_mass'];
    $goal_weight = $_POST['goal_weight'];
    $goal_body_fat_percentage = $_POST['goal_body_fat_percentage'];
    $notes = $_POST['notes'];

    // Insert progress tracking data
    $stmt = $conn->prepare("INSERT INTO progress_tracking (member_id, date, weight, height, bmi, body_fat_percentage, muscle_mass, goal_weight, goal_body_fat_percentage, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isddddddds", $member_id, $date, $weight, $height, $bmi, $body_fat_percentage, $muscle_mass, $goal_weight, $goal_body_fat_percentage, $notes);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Progress updated successfully</div>";

        $notification_message = "Your progress has been updated for $date.";
        $notification_stmt = $conn->prepare("INSERT INTO notifications (member_id, message) VALUES (?, ?)");
        $notification_stmt->bind_param("is", $member_id, $notification_message);
        $notification_stmt->execute();

        // Check and update milestones
        $milestones_stmt = $conn->prepare("SELECT * FROM milestones WHERE member_id=? AND achieved=0");
        $milestones_stmt->bind_param("i", $member_id);
        $milestones_stmt->execute();
        $milestones_result = $milestones_stmt->get_result();

        while ($milestone_row = $milestones_result->fetch_assoc()) {
            $milestone_id = $milestone_row['id'];
            $milestone_text = $milestone_row['milestone'];

            // Example check: if milestone is "Reach weight 70kg" and current weight is 70kg
            if (strpos($milestone_text, 'Reach weight') !== false) {
                $target_weight = (float)filter_var($milestone_text, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                if ($weight <= $target_weight) {
                    $update_milestone_stmt = $conn->prepare("UPDATE milestones SET achieved=1 WHERE id=?");
                    $update_milestone_stmt->bind_param("i", $milestone_id);
                    $update_milestone_stmt->execute();

                    // Notify member
                    $notification_message = "Congratulations! You've achieved the milestone: $milestone_text";
                    $notification_stmt = $conn->prepare("INSERT INTO notifications (member_id, message) VALUES (?, ?)");
                    $notification_stmt->bind_param("is", $member_id, $notification_message);
                    $notification_stmt->execute();
                }
            }

            // Add other milestone checks here...
        }

    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

$member_sql = "SELECT * FROM members";
$member_result = $conn->query($member_sql);
?>

<div class="container mt-5">
    <h2>Update Member Progress</h2>
    <?php if (!empty($message)) echo $message; ?>
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
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="weight">Weight (kg):</label>
            <input type="number" step="0.1" id="weight" name="weight" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="height">Height (cm):</label>
            <input type="number" step="0.1" id="height" name="height" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="body_fat_percentage">Body Fat Percentage (%):</label>
            <input type="number" step="0.1" id="body_fat_percentage" name="body_fat_percentage" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="muscle_mass">Muscle Mass (kg):</label>
            <input type="number" step="0.1" id="muscle_mass" name="muscle_mass" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="goal_weight">Goal Weight (kg):</label>
            <input type="number" step="0.1" id="goal_weight" name="goal_weight" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="goal_body_fat_percentage">Goal Body Fat Percentage (%):</label>
            <input type="number" step="0.1" id="goal_body_fat_percentage" name="goal_body_fat_percentage" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Progress</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
