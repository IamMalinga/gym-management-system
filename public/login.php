<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('../includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = md5($password); // Assuming MD5 was used for password hashing

    // Check Member
    $sql = "SELECT id, email, verified FROM members WHERE email='$email' AND password='$hashed_password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['verified'] == 0) {
            $_SESSION['email'] = $row['email'];
            header("Location: ../public/verify_otp.php");
            exit();
        } else {
            $_SESSION['username'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = 'member';
            header("Location: ../members/dashboard.php");
            exit();
        }
    }

    // Check Admin
    $sql = "SELECT id, email FROM admins WHERE email='$email' AND password='$hashed_password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = 'admin';
        header("Location: ../admins/dashboard.php");
        exit();
    }

    // Check Trainer
    $sql = "SELECT id, email FROM trainers WHERE email='$email' AND password='$hashed_password'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['role'] = 'trainer';
        header("Location: ../trainers/dashboard.php");
        exit();
    }

    // If no match found
    $error = "Invalid email or password";
}
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center">Login</h2>
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
            <form method="post" action="">
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
