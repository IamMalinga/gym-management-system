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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $member_id = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Member deleted successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $member_id = $_POST['update_id'];
    $name = $_POST['edit_name'];
    $email = $_POST['edit_email'];
    $phone = $_POST['edit_phone'];
    $address = $_POST['edit_address'];
    $dob = $_POST['edit_dob'];
    $gender = $_POST['edit_gender'];

    $stmt = $conn->prepare("UPDATE members SET name = ?, email = ?, phone = ?, address = ?, dob = ?, gender = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $dob, $gender, $member_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Member updated successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$sql = "SELECT * FROM members";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Manage Members</h2>
    <div class="mb-3">
        <input type="text" class="form-control" id="search" placeholder="Search members...">
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="memberTable">
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['dob']); ?></td>
                        <td><?php echo $row['gender'] == 'M' ? 'Male' : 'Female'; ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" onclick="editMember(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['name'])); ?>', '<?php echo htmlspecialchars(addslashes($row['email'])); ?>', '<?php echo htmlspecialchars(addslashes($row['phone'])); ?>', '<?php echo htmlspecialchars(addslashes($row['address'])); ?>', '<?php echo htmlspecialchars(addslashes($row['dob'])); ?>', '<?php echo $row['gender']; ?>')">Edit</button>
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
                Are you sure you want to delete this member?
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

<!-- Edit Member Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Member</h5>
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
                        <label for="edit_address" class="form-label">Address:</label>
                        <textarea id="edit_address" name="edit_address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_dob" class="form-label">DOB:</label>
                        <input type="date" id="edit_dob" name="edit_dob" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_gender" class="form-label">Gender:</label>
                        <select id="edit_gender" name="edit_gender" class="form-control" required>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
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
        var memberRows = document.querySelectorAll('#memberTable tr');

        memberRows.forEach(function(row) {
            var name = row.cells[0].innerText.toLowerCase();
            var email = row.cells[1].innerText.toLowerCase();
            var phone = row.cells[2].innerText.toLowerCase();
            var address = row.cells[3].innerText.toLowerCase();
            var dob = row.cells[4].innerText.toLowerCase();
            var gender = row.cells[5].innerText.toLowerCase();

            if (name.includes(searchText) || email.includes(searchText) || phone.includes(searchText) || address.includes(searchText) || dob.includes(searchText) || gender.includes(searchText)) {
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

    function editMember(id, name, email, phone, address, dob, gender) {
        document.getElementById('update_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_dob').value = dob;
        document.getElementById('edit_gender').value = gender;
        $('#editModal').modal('show');
    }
</script>

<?php include('../includes/footer.php'); ?>
