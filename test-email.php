<?php
$to = "kelly.ovita@example.com"; // Change this to the recipient's email address
$subject = "Test Email from XAMPP";
$message = "If you received this email, PHP mail() is working!";
$headers = "From: kellyovvy@gmail.com"; // Your email address used in sendmail.ini

if (mail($to, $subject, $message, $headers)) {
    echo "Mail sent successfully!";
} else {
    echo "Mail sending failed.";
}
?>
