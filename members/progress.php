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
$member_sql = "SELECT id FROM members WHERE email='$username'";
$member_result = $conn->query($member_sql);
$member_row = $member_result->fetch_assoc();
$member_id = $member_row['id'];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $bmi = $weight / (($height / 100) * ($height / 100));
    $body_fat_percentage = $_POST['body_fat_percentage'];
    $muscle_mass = $_POST['muscle_mass'];
    $goal_weight = $_POST['goal_weight'];
    $goal_body_fat_percentage = $_POST['goal_body_fat_percentage'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO progress_tracking (member_id, date, weight, height, bmi, body_fat_percentage, muscle_mass, goal_weight, goal_body_fat_percentage, notes) 
            VALUES ('$member_id', '$date', '$weight', '$height', '$bmi', '$body_fat_percentage', '$muscle_mass', '$goal_weight', '$goal_body_fat_percentage', '$notes')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success mt-3'>Progress updated successfully</div>";
        $notification_message = "Your progress has been updated for $date.";
        $notification_sql = "INSERT INTO notifications (member_id, message) VALUES ('$member_id', '$notification_message')";
        $conn->query($notification_sql);
    } else {
        $message = "<div class='alert alert-danger mt-3'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

$progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
$progress_result = $conn->query($progress_sql);
$dates = [];
$weights = [];
$bmis = [];
$body_fats = [];
$muscle_masses = [];
$goal_weights = [];
$goal_body_fats = [];
while($progress_row = $progress_result->fetch_assoc()) {
    $dates[] = $progress_row['date'];
    $weights[] = $progress_row['weight'];
    $bmis[] = $progress_row['bmi'];
    $body_fats[] = $progress_row['body_fat_percentage'];
    $muscle_masses[] = $progress_row['muscle_mass'];
    $goal_weights[] = $progress_row['goal_weight'];
    $goal_body_fats[] = $progress_row['goal_body_fat_percentage'];
}

$progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
$progress_result = $conn->query($progress_sql);

// Fetching Progress Reviews
$reviews_sql = "SELECT * FROM progress_reviews WHERE member_id='$member_id' ORDER BY date DESC";
$reviews_result = $conn->query($reviews_sql);

// Fetching Milestones
$milestones_sql = "SELECT * FROM milestones WHERE member_id='$member_id' ORDER BY date DESC";
$milestones_result = $conn->query($milestones_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Track Progress</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="post" action="" class="card p-4 shadow-sm">
        <div class="row mb-3">
            <div class="col">
                <label for="date" class="form-label">Date:</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            <div class="col">
                <label for="weight" class="form-label">Weight (kg):</label>
                <input type="number" step="0.1" id="weight" name="weight" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="height" class="form-label">Height (cm):</label>
                <input type="number" step="0.1" id="height" name="height" class="form-control" required>
            </div>
            <div class="col">
                <label for="body_fat_percentage" class="form-label">Body Fat Percentage (%):</label>
                <input type="number" step="0.1" id="body_fat_percentage" name="body_fat_percentage" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="muscle_mass" class="form-label">Muscle Mass (kg):</label>
                <input type="number" step="0.1" id="muscle_mass" name="muscle_mass" class="form-control" required>
            </div>
            <div class="col">
                <label for="goal_weight" class="form-label">Goal Weight (kg):</label>
                <input type="number" step="0.1" id="goal_weight" name="goal_weight" class="form-control" required>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="goal_body_fat_percentage" class="form-label">Goal Body Fat Percentage (%):</label>
                <input type="number" step="0.1" id="goal_body_fat_percentage" name="goal_body_fat_percentage" class="form-control" required>
            </div>
            <div class="col">
                <label for="notes" class="form-label">Notes:</label>
                <textarea id="notes" name="notes" class="form-control"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Progress</button>
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

    <h2 class="text-center mt-5 mb-4">Progress Visualization</h2>
    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="progressChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="goalChart"></canvas>
        </div>
    </div>

    <h2 class="text-center mt-5 mb-4">Progress Reviews</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Review</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $reviews_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['review']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <h2 class="text-center mt-5 mb-4">Milestones Achieved</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Date</th>
                    <th>Milestone</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $milestones_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['milestone_name']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('progressChart').getContext('2d');
    var progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Weight (kg)',
                data: <?php echo json_encode($weights); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'BMI',
                data: <?php echo json_encode($bmis); ?>,
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Body Fat (%)',
                data: <?php echo json_encode($body_fats); ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Muscle Mass (kg)',
                data: <?php echo json_encode($muscle_masses); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: false
                },
                y: {
                    beginAtZero: false
                }
            }
        }
    });

    var goalCtx = document.getElementById('goalChart').getContext('2d');
    var goalChart = new Chart(goalCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Goal Weight (kg)',
                data: <?php echo json_encode($goal_weights); ?>,
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Goal Body Fat (%)',
                data: <?php echo json_encode($goal_body_fats); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: false
                },
                y: {
                    beginAtZero: false
                }
            }
        }
    });
</script>

<?php include('../includes/footer.php'); ?>
