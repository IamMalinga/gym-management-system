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

// Fetch all members
$member_sql = "SELECT * FROM members";
$member_result = $conn->query($member_sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_progress'])) {
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
            $milestone_text = $milestone_row['milestone_name'];

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

// Update milestone
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_milestone'])) {
    $milestone_id = $_POST['milestone_id'];
    $achieved = $_POST['achieved'];
    $percentage = $_POST['percentage'];

    // Update milestone data
    $stmt = $conn->prepare("UPDATE milestones SET achieved=?, reward=? WHERE id=?");
    $stmt->bind_param("iii", $achieved, $percentage, $milestone_id);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Milestone updated successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-5">
    <h2>Select Member to View Progress</h2>
    <form method="post" action="" class="mb-4">
        <div class="form-group">
            <label for="member_id">Select Member:</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <?php while($row = $member_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">View Progress</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update_progress']) && !isset($_POST['update_milestone'])) {
        $member_id = $_POST['member_id'];

        $member_sql = "SELECT * FROM members WHERE id='$member_id'";
        $member_result = $conn->query($member_sql);
        if ($member_result->num_rows == 0) {
            echo "Member not found.";
            exit();
        }
        $member = $member_result->fetch_assoc();

        $progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
        $progress_result = $conn->query($progress_sql);

        $reviews_sql = "SELECT * FROM progress_reviews WHERE member_id='$member_id' ORDER BY date DESC";
        $reviews_result = $conn->query($reviews_sql);

        $milestones_sql = "SELECT * FROM milestones WHERE member_id='$member_id' ORDER BY date DESC";
        $milestones_result = $conn->query($milestones_sql);
    ?>
        <h2>Update Member Progress for <?php echo htmlspecialchars($member['name']); ?></h2>
        <?php if (!empty($message)) echo $message; ?>
        <form method="post" action="" class="mb-4">
            <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
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
            <button type="submit" name="update_progress" class="btn btn-primary">Update Progress</button>
        </form>

        <h3 class="mt-5 mb-4">Add Review</h3>
        <form method="post" action="add_review.php" class="mb-4">
            <div class="form-group">
                <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
                <label for="review_date">Date:</label>
                <input type="date" id="review_date" name="review_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="review">Review:</label>
                <textarea id="review" name="review" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
        </form>

        <h3 class="mt-5 mb-4">Add Milestone</h3>
        <form method="post" action="add_milestone.php">
            <div class="form-group">
                <input type="hidden" name="member_id" value="<?php echo $member_id; ?>">
                <label for="milestone_date">Date:</label>
                <input type="date" id="milestone_date" name="milestone_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="milestone">Milestone:</label>
                <textarea id="milestone" name="milestone" class="form-control" required></textarea>
            </div>
            <button type="submit" name="add_milestone" class="btn btn-primary">Add Milestone</button>
        </form>

        <h3 class="mt-5 mb-4">Progress History</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
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

        <h3 class="mt-5 mb-4">Reviews</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Review</th>
                        <th>Trainer</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $reviews_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['review']); ?></td>
                        <td><?php echo htmlspecialchars($row['trainer_id']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <h3 class="mt-5 mb-4">Milestones</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Milestone</th>
                        <th>Achieved</th>
                        <th>Percentage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $milestones_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['milestone_name']); ?></td>
                        <td><?php echo $row['achieved'] ? 'Yes' : 'No'; ?></td>
                        <td><?php echo htmlspecialchars($row['reward']); ?>%</td>
                        <td>
                            <form method="post" action="" class="form-inline">
                                <input type="hidden" name="milestone_id" value="<?php echo $row['id']; ?>">
                                <select name="achieved" class="form-control mb-2 mr-sm-2">
                                    <option value="0" <?php if (!$row['achieved']) echo 'selected'; ?>>No</option>
                                    <option value="1" <?php if ($row['achieved']) echo 'selected'; ?>>Yes</option>
                                </select>
                                <input type="number" name="percentage" class="form-control mb-2 mr-sm-2" value="<?php echo htmlspecialchars($row['reward']); ?>" min="0" max="100" required>
                                <button type="submit" name="update_milestone" class="btn btn-primary mb-2">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<?php include('../includes/footer.php'); ?>
