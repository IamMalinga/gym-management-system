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
    if (isset($_POST['approve_id'])) {
        $approve_id = $_POST['approve_id'];
        $update_sql = "UPDATE registrations SET is_approved = 1 WHERE id = '$approve_id'";
        $conn->query($update_sql);
    }

    if (isset($_POST['paid_id'])) {
        $paid_id = $_POST['paid_id'];
        $update_sql = "UPDATE registrations SET is_paid = 1 WHERE id = '$paid_id'";
        $conn->query($update_sql);
    }
}

$pending_approvals_sql = "SELECT r.*, m.name as member_name, c.name as course_name, pp.price as course_price FROM registrations r 
    JOIN members m ON r.user_id = m.id
    JOIN courses c ON r.course_workout_id = c.id
    JOIN pricing_plans pp ON c.plan_id = pp.id
    WHERE r.is_approved = 0";

$pending_approvals_result = $conn->query($pending_approvals_sql);

$approved_not_paid_sql = "SELECT r.*, m.name as member_name, c.name as course_name, pp.price as course_price FROM registrations r 
    JOIN members m ON r.user_id = m.id
    JOIN courses c ON r.course_workout_id = c.id
    JOIN pricing_plans pp ON c.plan_id = pp.id
    WHERE r.is_approved = 1 AND r.is_paid = 0";

$approved_not_paid_result = $conn->query($approved_not_paid_sql);

$approved_paid_sql = "SELECT r.*, m.name as member_name, c.name as course_name, pp.price as course_price FROM registrations r 
    JOIN members m ON r.user_id = m.id
    JOIN courses c ON r.course_workout_id = c.id
    JOIN pricing_plans pp ON c.plan_id = pp.id
    WHERE r.is_approved = 1 AND r.is_paid = 1";

$approved_paid_result = $conn->query($approved_paid_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Give Approvals</h2>
    <ul class="nav nav-tabs" id="approvalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-approvals-tab" data-bs-toggle="tab" data-bs-target="#pending-approvals" type="button" role="tab" aria-controls="pending-approvals" aria-selected="true">Pending Approvals</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-unpaid-tab" data-bs-toggle="tab" data-bs-target="#approved-unpaid" type="button" role="tab" aria-controls="approved-unpaid" aria-selected="false">Approved but Unpaid</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-paid-tab" data-bs-toggle="tab" data-bs-target="#approved-paid" type="button" role="tab" aria-controls="approved-paid" aria-selected="false">Approved and Paid</button>
        </li>
    </ul>
    <div class="tab-content" id="approvalTabsContent">
        <div class="tab-pane fade show active" id="pending-approvals" role="tabpanel" aria-labelledby="pending-approvals-tab">
            <h3 class="mt-4">Pending Approvals</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Course</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $pending_approvals_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_price']); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="approve_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="approved-unpaid" role="tabpanel" aria-labelledby="approved-unpaid-tab">
            <h3 class="mt-4">Approved but Unpaid</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Course</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $approved_not_paid_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_price']); ?></td>
                            <td>
                                <form method="post" action="">
                                    <input type="hidden" name="paid_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-primary">Mark as Paid</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="approved-paid" role="tabpanel" aria-labelledby="approved-paid-tab">
            <h3 class="mt-4">Approved and Paid</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Course</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $approved_paid_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['member_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>

<!-- Include Bootstrap JS for tab functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
