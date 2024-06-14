<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
$plans_result = $conn->query("SELECT * FROM pricing_plans");
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Pricing</h1>
        <p class="lead">Check out our membership plans and pricing details.</p>
    </div>

    <div class="row justify-content-center">
        <?php while($plan = $plans_result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card text-center pricing-card shadow h-100" style="border-radius: 10px;">
                    <div class="card-body">
                        <i class="<?php echo htmlspecialchars($plan['icon']); ?> fa-3x mb-3 text-primary"></i>
                        <h3 class="card-title"><?php echo htmlspecialchars($plan['name']); ?></h3>
                        <h4 class="card-title">$<?php echo htmlspecialchars($plan['price']); ?></h4>
                        <p class="card-text"><?php echo htmlspecialchars($plan['duration']); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars($plan['description']); ?></p>
                        <a href="#" class="btn btn-primary">Sign Up</a>
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
