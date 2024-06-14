<?php include('../includes/db_connect.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

$messages_sql = "SELECT id, name, email, message, response FROM contact_messages";
$messages_result = $conn->query($messages_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Contact Messages</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Response</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $messages_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td><?php echo htmlspecialchars($row['response']); ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="showReplyForm(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['email']); ?>', '<?php echo htmlspecialchars($row['message']); ?>')">Reply</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Reply Form Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="replyModalLabel">Reply to Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="send_reply.php" method="post">
                        <input type="hidden" id="message_id" name="message_id">
                        <input type="hidden" id="email" name="email">
                        <input type="hidden" id="original_message" name="original_message">
                        <div class="mb-3">
                            <label for="reply" class="form-label">Reply:</label>
                            <textarea id="reply" name="reply" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showReplyForm(messageId, email, originalMessage) {
            document.getElementById('message_id').value = messageId;
            document.getElementById('email').value = email;
            document.getElementById('original_message').value = originalMessage;
            var replyModal = new bootstrap.Modal(document.getElementById('replyModal'));
            replyModal.show();
        }
    </script>
</body>
</html>
