<?php
include('../includes/db_connect.php');

// Session check
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ob_start(); // Start output buffering
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Handle file upload
    $target_dir = "../assets/uploads/";
    $original_filename = basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    $uploadOk = 1;

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
            $image = $new_filename;
            // Insert blog post into the database
            $stmt = $conn->prepare("INSERT INTO blogs (title, content, image) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $content, $image);
            $stmt->execute();
            $stmt->close();

            header("Location: admin_manage_blogs.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    ob_end_flush(); // Flush the output buffer
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blogs</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Manage Blogs</h1>
    <form method="post" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <div id="editor" style="height: 300px;"></div>
            <input type="hidden" id="content" name="content">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" id="image" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2 class="text-center mt-5">All Blogs</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $blogs_result = $conn->query("SELECT * FROM blogs");
        while($row = $blogs_result->fetch_assoc()):
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><img src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Blog Image" style="width: 100px;"></td>
                <td>
                    <button class="btn btn-danger" onclick="deleteBlog(<?php echo $row['id']; ?>)">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    document.querySelector('form').onsubmit = function() {
        document.querySelector('#content').value = quill.root.innerHTML;
    };

    function deleteBlog(id) {
        if (confirm('Are you sure you want to delete this blog?')) {
            window.location.href = 'delete_blog.php?id=' + id;
        }
    }
</script>
</body>
</html>
<?php include('../includes/footer.php'); ?>
