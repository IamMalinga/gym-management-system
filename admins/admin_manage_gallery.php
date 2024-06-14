<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

include('../includes/db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'add') {
        $caption = $_POST['caption'];

        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = "assets/images/" . basename($_FILES["image"]["name"]);
                // Insert gallery item into the database
                $stmt = $conn->prepare("INSERT INTO gallery (image_url, caption) VALUES (?, ?)");
                $stmt->bind_param("ss", $image_url, $caption);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $caption = $_POST['caption'];
        $image_url = $_POST['image_url'];

        if (!empty($_FILES["image"]["name"])) {
            $target_dir = "../assets/images/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["image"]["size"] > 5000000) { // 5MB limit
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = "assets/images/" . basename($_FILES["image"]["name"]);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        }

        $stmt = $conn->prepare("UPDATE gallery SET image_url = ?, caption = ? WHERE id = ?");
        $stmt->bind_param("ssi", $image_url, $caption, $id);
        $stmt->execute();
        $stmt->close();

    } elseif ($action == 'delete') {
        $id = $_POST['id'];

        // Delete the image file
        $stmt = $conn->prepare("SELECT image_url FROM gallery WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($image_url);
        $stmt->fetch();
        $stmt->close();
        unlink("../" . $image_url);

        // Delete the gallery record
        $stmt = $conn->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_manage_gallery.php");
    exit();
}

$gallery_result = $conn->query("SELECT * FROM gallery ORDER BY id DESC");
?>

<?php include('../includes/header.php'); ?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Manage Gallery</h1>
    
    <h2 class="text-center mt-5">Add New Image</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" id="image" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <input type="text" id="caption" name="caption" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Image</button>
    </form>

    <h2 class="text-center mt-5">Gallery</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Caption</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $gallery_result->fetch_assoc()): ?>
            <tr data-id="<?php echo $row['id']; ?>">
                <td class="image-url"><img src="../<?php echo htmlspecialchars($row['image_url']); ?>" alt="Gallery Image" style="width: 100px;"></td>
                <td class="caption"><?php echo htmlspecialchars($row['caption']); ?></td>
                <td>
                    <button class="btn btn-warning" onclick="editGallery(<?php echo $row['id']; ?>)">Edit</button>
                    <form method="post" action="" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function editGallery(id) {
        const row = document.querySelector(`[data-id='${id}']`);
        const imageUrl = row.querySelector('.image-url img').src;
        const caption = row.querySelector('.caption').textContent;

        document.querySelector('#image').value = imageUrl;
        document.querySelector('#caption').value = caption;
        document.querySelector('input[name="action"]').value = 'update';
        document.querySelector('input[name="id"]').value = id;
    }
</script>

<?php include('../includes/footer.php'); ?>
