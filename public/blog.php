<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Our Blog</h1>
    <div class="row">
    <?php
    $blogs_result = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
    while($row = $blogs_result->fetch_assoc()):
    ?>
        <div class="col-md-4 mb-4">
            <div class="card">
                <img src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Blog Image">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <p class="card-text"><?php echo substr(strip_tags($row['content']), 0, 100); ?>...</p>
                    <a href="blog_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }
</style>

<?php include('../includes/footer.php'); ?>
