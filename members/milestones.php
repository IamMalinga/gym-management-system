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

$milestones_sql = "SELECT * FROM milestones WHERE member_id='$member_id'";
$milestones_result = $conn->query($milestones_sql);

$not_achieved_count = 0;
$total_milestones = 0;
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Your Milestones</h2>
    <div class="row">
        <!-- Pie Chart on the left -->
        <div class="col-lg-6 col-md-12 mb-4">
            <h3 class="text-center">Milestone Summary</h3>
            <canvas id="milestoneChart" class="shadow-sm p-3 bg-white rounded"></canvas>
        </div>
        
        <!-- Milestone Cards on the right -->
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <?php while($row = $milestones_result->fetch_assoc()): 
                    $total_milestones++;
                    if ($row['achieved'] == 0) { // This checks if the milestone is not achieved
                        $not_achieved_count++;
                    }
                    $progress = $row['reward']; // Assuming reward indicates progress percentage
                ?>
                    <div class="col-md-12 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($row['milestone_name']); ?></h5>
                                    <span class="badge <?php echo $row['achieved'] ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo $row['achieved'] ? 'Achieved' : 'Pending'; ?>
                                    </span>
                                </div>
                                <p class="card-text"><strong>Achieved:</strong> <?php echo $row['achieved'] ? 'Yes' : 'No'; ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                                <?php if ($row['achieved']): ?>
                                    <p class="card-text">
                                        <strong>Reward:</strong> 
                                        <span data-bs-toggle="tooltip" title="You have earned a reward for this milestone!">
                                            🎉 Reward Points: <?php echo htmlspecialchars($row['reward']); ?>
                                        </span>
                                    </p>
                                    <div class="progress mt-3">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                                    </div>
                                <?php else: ?>
                                    <div class="progress mt-3">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $progress; ?>%" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $progress; ?>%</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<!-- Include Bootstrap JS for tooltip functionality -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    var ctx = document.getElementById('milestoneChart').getContext('2d');
    var milestoneChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Achieved', 'Not Achieved'],
            datasets: [{
                data: [<?php echo $total_milestones - $not_achieved_count; ?>, <?php echo $not_achieved_count; ?>],
                backgroundColor: ['#28a745', '#ffc107'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });
</script>

<!-- CSS for Styling -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 500;
    }
    .progress {
        height: 20px;
    }
    .progress-bar {
        line-height: 20px;
    }
    .badge {
        font-size: 1rem;
        padding: 0.5em 1em;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
</style>
