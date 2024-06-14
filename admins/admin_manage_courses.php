<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plan_id = $_POST['plan_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE courses SET plan_id=?, name=?, description=? WHERE id=?");
        $stmt->bind_param("issi", $plan_id, $name, $description, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO courses (plan_id, name, description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $plan_id, $name, $description);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: admin_manage_courses.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM courses WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_manage_courses.php");
    exit();
}

$plans_result = $conn->query("SELECT * FROM pricing_plans");
$courses_result = $conn->query("SELECT * FROM courses");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Manage Courses</h1>
    <form method="post" action="">
        <div class="mb-3">
            <label for="plan_id" class="form-label">Pricing Plan</label>
            <select id="plan_id" name="plan_id" class="form-control" required>
                <?php while($plan = $plans_result->fetch_assoc()): ?>
                    <option value="<?php echo $plan['id']; ?>"><?php echo htmlspecialchars($plan['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Course Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" required></textarea>
        </div>
        <input type="hidden" id="id" name="id">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2 class="text-center mt-5">All Courses</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Pricing Plan</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $courses_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['plan_id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                    <button class="btn btn-warning" onclick="editCourse(<?php echo $row['id']; ?>, <?php echo $row['plan_id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>', '<?php echo htmlspecialchars($row['description']); ?>')">Edit</button>
                    <a href="admin_manage_courses.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function editCourse(id, plan_id, name, description) {
        document.getElementById('id').value = id;
        document.getElementById('plan_id').value = plan_id;
        document.getElementById('name').value = name;
        document.getElementById('description').value = description;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include('../includes/footer.php'); ?>
