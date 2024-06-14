<?php
require '../libs/PHPMailer/src/Exception.php';
require '../libs/PHPMailer/src/PHPMailer.php';
require '../libs/PHPMailer/src/SMTP.php';
include('../includes/db_connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = $_POST['message_id'];
    $reply = $_POST['reply'];
    $email = $_POST['email'];
    $original_message = $_POST['original_message'];

    // Fetch the name of the user
    $message_sql = "SELECT name FROM contact_messages WHERE id=?";
    $stmt = $conn->prepare($message_sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $stmt->bind_result($name);
    $stmt->fetch();
    $stmt->close();

    // Update the response in the database
    $update_sql = "UPDATE contact_messages SET response=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $reply, $message_id);
    $stmt->execute();
    $stmt->close();

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com'; // Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Username = 'xxxxxxxxxxx@outlook.com'; // SMTP username
        $mail->Password = 'Set up your password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
    
        // Disable SSL verification (for development purposes only)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    
        // Recipients
        $mail->setFrom('malinga_samarakoon@outlook.com', 'Your Gym Name');
        $mail->addAddress($email, $name);
    
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Reply to your message';
        $mail->Body    = "<p>Dear $name,</p>
                          <p>Thank you for your message. Here is our response:</p>
                          <p><strong>Your message:</strong></p>
                          <p>$original_message</p>
                          <p><strong>Our reply:</strong></p>
                          <p>$reply</p>
                          <p>Best regards,<br>Your Gym</p>";
    
        $mail->send();
        $_SESSION['message'] = "Reply sent successfully.";
    } catch (Exception $e) {
        $_SESSION['message'] = "Failed to send the reply. Mailer Error: {$mail->ErrorInfo}";
    }
    

    header("Location: admin_view_messages.php");
    exit();
}
?>
