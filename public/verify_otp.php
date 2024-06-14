<?php
include('../includes/db_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function maskEmail($email) {
    $mail_parts = explode("@", $email);
    $domain_parts = explode('.', $mail_parts[1]);

    $mail_parts[0] = str_repeat('*', strlen($mail_parts[0]) - 1) . substr($mail_parts[0], -1);
    $domain_parts[0] = substr($domain_parts[0], 0, 1) . str_repeat('*', strlen($domain_parts[0]) - 1);
    
    return $mail_parts[0] . '@' . $domain_parts[0] . '.' . $domain_parts[1];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $otp = $_POST['otp'];

    $sql = "SELECT otp FROM members WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($row['otp'] == $otp) {
            $update_sql = "UPDATE members SET verified=1 WHERE email='$email'";
            $conn->query($update_sql);
            $_SESSION['message'] = "Account verified successfully!";
            header("Location: login.php");
            exit();
        } else {
            $error = "Invalid OTP";
        }
    } else {
        $error = "Invalid email address";
    }
}

$email = $_SESSION['email'];
$masked_email = maskEmail($email);
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center">Verify OTP</h2>
                    <p class="text-center">An OTP has been sent to your email: <strong><?php echo $masked_email; ?></strong></p>
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <?php if (isset($_SESSION['message'])) { echo "<div class='alert alert-success'>{$_SESSION['message']}</div>"; unset($_SESSION['message']); } ?>
                    <form method="post" action="">
                        <div class="form-group mb-3">
                            <label for="otp" class="form-label">OTP:</label>
                            <input type="text" class="form-control" id="otp" name="otp" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
