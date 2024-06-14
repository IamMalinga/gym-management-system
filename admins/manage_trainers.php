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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $specialization = $_POST['specialization'];

    $stmt = $conn->prepare("INSERT INTO trainers (name, email, password, phone, specialization) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $specialization);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Trainer added successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $trainer_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM trainers WHERE id = ?");
    $stmt->bind_param("i", $trainer_id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Trainer deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $trainer_id = $_POST['update_id'];
    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $phone = $_POST['edit_phone'];
    $specialization = $_POST['edit_specialization'];

    $stmt = $conn->prepare("UPDATE trainers SET name = ?, email = ?, phone = ?, specialization = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $email, $phone, $specialization, $trainer_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Trainer updated successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$trainers_sql = "SELECT * FROM trainers";
$trainers_result = $conn->query($trainers_sql);
?>

<div class="container mt-5">
    <h2>Manage Trainers</h2>

    <h3>Add New Trainer</h3>
    <form method="post" action="" class="mb-4">
        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone:</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization:</label>
            <textarea id="specialization" name="specialization" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Trainer</button>
    </form>

    <h3>Existing Trainers</h3>
    <div class="mb-3">
        <input type="text" class="form-control" id="search" placeholder="Search trainers...">
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Specialization</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="trainerTable">
                <?php while($row = $trainers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="editTrainer(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['name'])); ?>', '<?php echo htmlspecialchars(addslashes($row['email'])); ?>', '<?php echo htmlspecialchars(addslashes($row['phone'])); ?>', '<?php echo htmlspecialchars(addslashes($row['specialization'])); ?>')">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this trainer?
            </div>
            <div class="modal-footer">
                <form method="post" action="" id="deleteForm">
                    <input type="hidden" name="delete_id" id="delete_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Trainer Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Trainer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name:</label>
                        <input type="text" id="edit_name" name="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email:</label>
                        <input type="email" id="edit_email" name="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone:</label>
                        <input type="text" id="edit_phone" name="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_specialization" class="form-label">Specialization:</label>
                        <textarea id="edit_specialization" name="edit_specialization" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {
        var searchText = this.value.toLowerCase();
        var trainerRows = document.querySelectorAll('#trainerTable tr');

        trainerRows.forEach(function(row) {
            var name = row.cells[0].innerText.toLowerCase();
            var email = row.cells[1].innerText.toLowerCase();
            var phone = row.cells[2].innerText.toLowerCase();
            var specialization = row.cells[3].innerText.toLowerCase();

            if (name.includes(searchText) || email.includes(searchText) || phone.includes(searchText) || specialization.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function confirmDelete(id) {
        document.getElementById('delete_id').value = id;
        $('#deleteModal').modal('show');
    }

    function editTrainer(id, name, email, phone, specialization) {
        document.getElementById('update_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_specialization').value = specialization;
        $('#editModal').modal('show');
    }
</script>

<?php include('../includes/footer.php'); ?>
