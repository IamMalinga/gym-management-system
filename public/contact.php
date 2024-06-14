<?php include('../includes/header.php'); ?>
<div class="container mt-5">
    <h1 class="text-center">Contact Us</h1>
    <p class="text-center">Get in touch with us for any queries or information.</p>
    <form action="contact_form.php" method="post" class="mt-4">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
        </div>
        <div class="d-grid">
            <input type="submit" value="Send" class="btn btn-primary">
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
