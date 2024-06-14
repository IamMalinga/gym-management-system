<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
$sql = "SELECT * FROM pricing_plans LIMIT 4";
$result = $conn->query($sql);

// Fetch latest 3 images for the gallery section
$gallery_sql = "SELECT * FROM gallery ORDER BY id DESC LIMIT 3";
$gallery_result = $conn->query($gallery_sql);
?>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<!-- Hero Section -->
<section id="hero" class="text-center text-white" style="background-image: url('../assets/images/gym-background.jpg'); background-size: cover; background-position: center; padding: 150px 0; position: relative; font-family: 'Roboto', sans-serif;">
    <div class="container position-relative">
        <img src="../assets/images/logo.jpg" alt="Gym Trainer" class="rounded-circle border border-white" style="width: 150px; height: 150px; position: absolute; top: -75px; right: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
        <div style="background: rgba(0, 0, 0, 0.7); padding: 40px; border-radius: 10px;">
            <h1>Welcome to the Weerasuriya fitness gym</h1>
            <p>Your fitness journey starts here. Explore our services and join us to achieve your fitness goals.</p>
            <a href="#services" class="btn btn-primary mt-3">Explore Services</a>
        </div>
    </div>
</section>

<!-- What We Offer Section -->
<section id="services" class="py-5 bg-light text-center" style="font-family: 'Roboto', sans-serif;">
    <div class="container">
        <h2 class="mb-4">What We Offer</h2>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="service-box animate" style="background-image: url('../assets/images/personal-training.jpg'); background-size: cover; height: 250px; position: relative; color: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                    <div class="position-absolute bottom-0 start-0 p-3" style="background: rgba(0, 0, 0, 0.7); width: 100%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                        <h3>Personal Training</h3>
                        <button class="btn btn-dark btn-sm">View Courses</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="service-box animate" style="background-image: url('../assets/images/group-training.jpg'); background-size: cover; height: 250px; position: relative; color: white; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                    <div class="position-absolute bottom-0 start-0 p-3" style="background: rgba(0, 0, 0, 0.7); width: 100%; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                        <h3>Group Training</h3>
                        <button class="btn btn-dark btn-sm">View Courses</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 animate" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                    <img src="../assets/images/body-building.jpg" alt="Body Building" class="card-img-top" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <div class="card-body">
                        <h4 class="card-title">Body Building</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 animate" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                    <img src="../assets/images/muscle-gain.jpg" alt="Muscle Gain" class="card-img-top" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <div class="card-body">
                        <h4 class="card-title">Muscle Gain</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 animate" style="border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s;">
                    <img src="../assets/images/weight-loss.jpg" alt="Weight Loss" class="card-img-top" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <div class="card-body">
                        <h4 class="card-title">Weight Loss</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-5 text-center" style="font-family: 'Roboto', sans-serif;">
    <div class="container">
        <h2 class="mb-4">Gallery</h2>
        <div class="d-flex justify-content-center flex-wrap">
            <?php while($image = $gallery_result->fetch_assoc()): ?>
                <img src="../<?php echo htmlspecialchars($image['image_url']); ?>" alt="Gallery Image" class="img-fluid m-2 gallery-image animate" style="transition: transform 0.3s;">
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-5 bg-light text-center" style="font-family: 'Roboto', sans-serif;">
    <div class="container">
        <h2 class="mb-4">Pricing Plans</h2>
        <div class="row">
            <?php while($plan = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-4">
                    <div class="card text-center pricing-card shadow h-100 animate" style="border-radius: 10px; transition: transform 0.3s;">
                        <div class="card-body">
                            <i class="<?php echo htmlspecialchars($plan['icon']); ?> fa-3x mb-3 text-primary"></i>
                            <h3 class="card-title"><?php echo htmlspecialchars($plan['name']); ?></h3>
                            <h4 class="card-title">$<?php echo htmlspecialchars($plan['price']); ?></h4>
                            <p class="card-text"><?php echo htmlspecialchars($plan['duration']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($plan['description']); ?></p>
                            <a href="#" class="btn btn-primary sign-up" data-id="<?php echo $plan['id']; ?>">Sign Up</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include('../includes/footer.php'); ?>

<!-- Include FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<!-- Smooth Scrolling Script -->
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>

<!-- Scroll Animation Script -->
<script>
document.addEventListener('scroll', function() {
    const elements = document.querySelectorAll('.service-box, .card, .gallery-image');
    elements.forEach(element => {
        const position = element.getBoundingClientRect().top;
        const screenPosition = window.innerHeight / 1.3;
        if (position < screenPosition) {
            element.classList.add('animate');
        }
    });
});
</script>

<!-- Sign Up Button Script -->
<script>
document.querySelectorAll('.sign-up').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        <?php if (isset($_SESSION['username'])): ?>
            window.location.href = '../members/dashboard.php';
        <?php else: ?>
            window.location.href = 'login.php';
        <?php endif; ?>
    });
});
</script>

<!-- CSS for Animations and Advanced Styles -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.animate {
    opacity: 0;
    animation: fadeInUp 1s ease-in-out forwards;
}

.service-box:hover,
.card:hover,
.gallery-image:hover {
    transform: scale(1.05);
}

.gallery-image {
    width: 300px;
    height: 300px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 50px;
    padding: 10px 20px;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
}

#services .service-box {
    background-blend-mode: multiply;
}

.card {
    transition: box-shadow 0.3s, transform 0.3s;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    transform: translateY(-10px);
}

.pricing-card .btn {
    border-radius: 20px;
}

#pricing h2,
#gallery h2,
#services h2 {
    font-size: 2.5rem;
    color: #333;
}

#services h3 {
    font-size: 1.5rem;
    color: #fff;
}
</style>
