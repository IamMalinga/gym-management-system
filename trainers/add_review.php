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
    $trainer_id = $_SESSION['user_id']; // Assuming the trainer ID is stored in session
    $review_date = $_POST['review_date'];
    $review = $_POST['review'];

    // Insert review data
    $stmt = $conn->prepare("INSERT INTO progress_reviews (member_id, trainer_id, date, review) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $member_id, $trainer_id, $review_date, $review);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Review added successfully</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<div class="container mt-5">
    <h2>Add Review</h2>
    <?php if (!empty($message)) echo $message; ?>
    <form method="post" action="" class="mb-4">
        <div class="form-group">
            <label for="review_date">Date:</label>
            <input type="date" id="review_date" name="review_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="review">Review:</label>
            <textarea id="review" name="review" class="form-control" required></textarea>
        </div>
        <button type="submit" name="add_review" class="btn btn-primary">Add Review</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
