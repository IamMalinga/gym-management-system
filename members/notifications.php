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

$notifications_sql = "SELECT * FROM notifications WHERE member_id='$member_id' ORDER BY created_at DESC";
$notifications_result = $conn->query($notifications_sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Notifications</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php while($notification = $notifications_result->fetch_assoc()): ?>
                <div class="card mb-3 shadow-sm <?php echo $notification['is_read'] ? '' : 'border-primary'; ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">
                                    <?php echo htmlspecialchars($notification['message']); ?>
                                    <?php if (!$notification['is_read']): ?>
                                        <span class="badge bg-primary ms-2">New</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text">
                                    <i class="bi bi-clock"></i> 
                                    <?php echo htmlspecialchars(date('F j, Y, g:i a', strtotime($notification['created_at']))); ?>
                                </p>
                            </div>
                            <div>
                                <?php if (!$notification['is_read']): ?>
                                    <a href="mark_as_read.php?id=<?php echo $notification['id']; ?>" class="btn btn-outline-primary btn-sm">Mark as read</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
