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
$sql = "SELECT * FROM members WHERE email='$username'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    $update_sql = "UPDATE members SET name='$name', phone='$phone', address='$address', dob='$dob', gender='$gender' WHERE email='$username'";
    if ($conn->query($update_sql) === TRUE) {
        $message = "Profile updated successfully.";
        $row['name'] = $name;
        $row['phone'] = $phone;
        $row['address'] = $address;
        $row['dob'] = $dob;
        $row['gender'] = $gender;
    } else {
        $message = "Error: " . $update_sql . "<br>" . $conn->error;
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Update Profile</h2>
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $message; ?>
                        </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone:</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address:</label>
                            <textarea id="address" name="address" class="form-control" required><?php echo htmlspecialchars($row['address']); ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="dob" class="form-label">Date of Birth:</label>
                            <input type="date" id="dob" name="dob" class="form-control" value="<?php echo htmlspecialchars($row['dob']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label">Gender:</label>
                            <select id="gender" name="gender" class="form-select" required>
                                <option value="M" <?php if ($row['gender'] == 'M') echo 'selected'; ?>>Male</option>
                                <option value="F" <?php if ($row['gender'] == 'F') echo 'selected'; ?>>Female</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <input type="submit" value="Update Profile" class="btn btn-primary btn-block">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
