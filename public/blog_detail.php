<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: blog.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img src="../assets/uploads/<?php echo htmlspecialchars($blog['image']); ?>" class="card-img-top" alt="Blog Image">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    <div class="card-text"><?php echo $blog['content']; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
