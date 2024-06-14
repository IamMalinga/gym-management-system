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

$member_sql = "SELECT * FROM members";
$member_result = $conn->query($member_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Visualize Member Data</h2>
    <form method="post" action="">
        <div class="form-group">
            <label for="member_id">Select Member:</label>
            <select id="member_id" name="member_id" class="form-control" required>
                <?php while($row = $member_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-3">View Data</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $member_id = $_POST['member_id'];

        $member_name_sql = "SELECT name FROM members WHERE id='$member_id'";
        $member_name_result = $conn->query($member_name_sql);
        $member_name_row = $member_name_result->fetch_assoc();
        $member_name = htmlspecialchars($member_name_row['name']);

        $progress_sql = "SELECT * FROM progress_tracking WHERE member_id='$member_id' ORDER BY date ASC";
        $progress_result = $conn->query($progress_sql);

        $attendance_sql = "SELECT * FROM attendance WHERE member_id='$member_id' ORDER BY check_in ASC";
        $attendance_result = $conn->query($attendance_sql);

        $dates = [];
        $weights = [];
        $heights = [];
        $bmis = [];
        $goal_weights = [];
        $body_fats = [];
        $muscle_masses = [];
        $goal_body_fats = [];
        while($row = $progress_result->fetch_assoc()) {
            $dates[] = $row['date'];
            $weights[] = $row['weight'];
            $heights[] = $row['height'];
            $bmis[] = $row['bmi'];
            $goal_weights[] = $row['goal_weight'];
            $body_fats[] = $row['body_fat_percentage'];
            $muscle_masses[] = $row['muscle_mass'];
            $goal_body_fats[] = $row['goal_body_fat_percentage'];
        }

        $attendance_dates = [];
        $check_ins = [];
        $check_outs = [];
        while($row = $attendance_result->fetch_assoc()) {
            $attendance_dates[] = $row['check_in'];
            $check_ins[] = $row['check_in'];
            $check_outs[] = $row['check_out'];
        }
    ?>
        <h3 class="text-center mt-5 mb-4">Progress Charts for <?php echo $member_name; ?></h3>
        <div class="row">
            <div class="col-md-6 mb-4">
                <canvas id="weightChart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <canvas id="heightChart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <canvas id="bmiChart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <canvas id="bodyFatChart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <canvas id="muscleMassChart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <canvas id="goalComparisonChart"></canvas>
            </div>
        </div>
        <h3 class="text-center mt-5 mb-4">Attendance Chart for <?php echo $member_name; ?></h3>
        <div class="row">
            <div class="col-md-12 mb-4">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    var weightCtx = document.getElementById('weightChart').getContext('2d');
    var weightChart = new Chart(weightCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Weight (kg)',
                data: <?php echo json_encode($weights); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var heightCtx = document.getElementById('heightChart').getContext('2d');
    var heightChart = new Chart(heightCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Height (cm)',
                data: <?php echo json_encode($heights); ?>,
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var bmiCtx = document.getElementById('bmiChart').getContext('2d');
    var bmiChart = new Chart(bmiCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'BMI',
                data: <?php echo json_encode($bmis); ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var bodyFatCtx = document.getElementById('bodyFatChart').getContext('2d');
    var bodyFatChart = new Chart(bodyFatCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Body Fat (%)',
                data: <?php echo json_encode($body_fats); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var muscleMassCtx = document.getElementById('muscleMassChart').getContext('2d');
    var muscleMassChart = new Chart(muscleMassCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Muscle Mass (kg)',
                data: <?php echo json_encode($muscle_masses); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var goalComparisonCtx = document.getElementById('goalComparisonChart').getContext('2d');
    var goalComparisonChart = new Chart(goalComparisonCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [{
                label: 'Actual Weight (kg)',
                data: <?php echo json_encode($weights); ?>,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Goal Weight (kg)',
                data: <?php echo json_encode($goal_weights); ?>,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Body Fat (%)',
                data: <?php echo json_encode($body_fats); ?>,
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Goal Body Fat (%)',
                data: <?php echo json_encode($goal_body_fats); ?>,
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });

    var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    var attendanceChart = new Chart(attendanceCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($attendance_dates); ?>,
            datasets: [{
                label: 'Check-ins',
                data: <?php echo json_encode($check_ins); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }, {
                label: 'Check-outs',
                data: <?php echo json_encode($check_outs); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { beginAtZero: false },
                y: { beginAtZero: false }
            }
        }
    });
    <?php endif; ?>
</script>

<?php include('../includes/footer.php'); ?>
