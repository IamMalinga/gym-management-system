<?php include('../includes/db_connect.php'); ?>
<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE notifications SET is_read = TRUE WHERE id = '$id'";
    $conn->query($sql);
}
header("Location: notifications.php");
?>
