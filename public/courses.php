<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>

<?php
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Courses</h2>
    <div class="row">
        <?php
        while($row = $result->fetch_assoc()) {
            echo "<div class='col-md-4'>";
            echo "<div class='card mb-4 shadow-sm'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . $row['name'] . "</h5>";
            echo "<p class='card-text'>" . $row['description'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
