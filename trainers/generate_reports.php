<?php 
include('../includes/header.php'); 
include('../includes/db_connect.php'); 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$member_sql = "SELECT * FROM members";
$member_result = $conn->query($member_sql);
?>

<h2 class="text-center mb-4">Generate Reports</h2>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="post" action="generate_report.php">
                    <div class="form-group">
                        <label for="member_id">Select Member:</label>
                        <select id="member_id" name="member_id" class="form-control" required>
                            <?php
                            while($row = $member_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="d-grid">
                        <input type="submit" value="Generate Report" class="btn btn-primary btn-block">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
