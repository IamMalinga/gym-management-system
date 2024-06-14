<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'member') {
    header("Location: ../public/login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM members WHERE email='$username'";
$result = $conn->query($sql);
$member = $result->fetch_assoc();
$member_id = $member['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id']) && isset($_POST['plan_id']) && isset($_POST['amount']) && isset($_POST['payment_method'])) {
    $course_id = $_POST['course_id'];
    $plan_id = $_POST['plan_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $payment_date = date('Y-m-d H:i:s');

    $payment_sql = "INSERT INTO payments (member_id, plan_id, course_id, amount, payment_date, payment_method) 
                    VALUES ('$member_id', '$plan_id', '$course_id', '$amount', '$payment_date', '$payment_method')";
    $conn->query($payment_sql);

    $update_registration_sql = "UPDATE registrations SET is_paid = 1 WHERE user_id = '$member_id' AND course_workout_id = '$course_id'";
    $conn->query($update_registration_sql);
}

$approved_courses_sql = "SELECT r.*, c.name as course_name, pp.price as course_price FROM registrations r 
                         JOIN courses c ON r.course_workout_id = c.id
                         JOIN pricing_plans pp ON c.plan_id = pp.id
                         WHERE r.user_id = '$member_id' AND r.is_approved = 1 AND r.is_paid = 0";
$approved_courses_result = $conn->query($approved_courses_sql);

$payment_history_sql = "SELECT p.*, c.name as course_name, pp.name as plan_name FROM payments p 
                        JOIN courses c ON p.course_id = c.id
                        JOIN pricing_plans pp ON p.plan_id = pp.id
                        WHERE p.member_id = '$member_id'";
$payment_history_result = $conn->query($payment_history_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Make Payments</h2>
    <h3 class="mt-4">Approved Courses for Payment</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Price</th>
                <th>Payment Method</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $approved_courses_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_price']); ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($row['course_workout_id']); ?>">
                            <input type="hidden" name="plan_id" value="<?php echo htmlspecialchars($row['course_workout_id']); // Assuming plan_id is same as course_workout_id ?>">
                            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($row['course_price']); ?>">
                            <select name="payment_method" required>
                                <option value="Card">Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                            </select>
                    </td>
                    <td>
                            <button type="submit" class="btn btn-primary">Pay</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-4">Payment History</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Course</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $payment_history_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['plan_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('../includes/footer.php'); ?>

<!-- Include Bootstrap JS for tab functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
