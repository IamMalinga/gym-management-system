<?php include('../includes/header.php'); ?>
<?php include('../includes/db_connect.php'); ?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: ../public/login.php");
    exit();
}

$sql = "SELECT * FROM payments";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>Manage Payments</h2>
    <div class="mb-3">
        <input type="text" class="form-control" id="search" placeholder="Search payments...">
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                </tr>
            </thead>
            <tbody id="paymentTable">
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['member_id']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['payment_date']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pagination justify-content-center">
            <!-- Pagination links will be inserted here by JavaScript -->
        </ul>
    </nav>
</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {
        var searchText = this.value.toLowerCase();
        var paymentRows = document.querySelectorAll('#paymentTable tr');

        paymentRows.forEach(function(row) {
            var memberId = row.cells[0].innerText.toLowerCase();
            var amount = row.cells[1].innerText.toLowerCase();
            var paymentDate = row.cells[2].innerText.toLowerCase();
            var paymentMethod = row.cells[3].innerText.toLowerCase();

            if (memberId.includes(searchText) || amount.includes(searchText) || paymentDate.includes(searchText) || paymentMethod.includes(searchText)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var rowsPerPage = 10;
        var rows = document.querySelectorAll('#paymentTable tr');
        var totalRows = rows.length;
        var totalPages = Math.ceil(totalRows / rowsPerPage);

        function showPage(page) {
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;

            rows.forEach(function(row, index) {
                if (index >= start && index < end) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            var paginationLinks = '';
            for (var i = 1; i <= totalPages; i++) {
                paginationLinks += `<li class="page-item${i === page ? ' active' : ''}"><a class="page-link" href="#">${i}</a></li>`;
            }
            document.querySelector('.pagination').innerHTML = paginationLinks;
        }

        document.querySelector('.pagination').addEventListener('click', function(event) {
            if (event.target.tagName === 'A') {
                event.preventDefault();
                showPage(Number(event.target.innerText));
            }
        });

        showPage(1);
    });
</script>

<?php include('../includes/footer.php'); ?>
