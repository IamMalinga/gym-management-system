<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
// Pagination logic
$limit = 10; // Number of images per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$total_results = $conn->query("SELECT COUNT(*) FROM gallery")->fetch_row()[0];
$total_pages = ceil($total_results / $limit);

$gallery_result = $conn->query("SELECT * FROM gallery ORDER BY id DESC LIMIT $start, $limit");
?>

<div class="container mt-5">
    <div class="gallery-header text-center mb-4">
        <h1>Gallery</h1>
        <p>Check out photos of our gym, trainers, and members in action.</p>
    </div>
    <div class="gallery">
        <div class="row">
            <?php while($row = $gallery_result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="gallery-item">
                        <img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="Gallery Image">
                        <div class="caption"><?php echo htmlspecialchars($row['caption']); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation example" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="gallery.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<style>
    .gallery-header {
        text-align: center;
    }
    .gallery-header h1 {
        font-size: 2.5rem;
    }
    .gallery-header p {
        font-size: 1.2rem;
    }
    .gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .gallery-item:hover {
        transform: scale(1.05);
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .gallery-item .caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-item:hover .caption {
        opacity: 1;
    }
</style>

<?php include('../includes/footer.php'); ?>
