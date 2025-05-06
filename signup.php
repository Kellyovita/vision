<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $phone = htmlspecialchars(trim($_POST["phone"]));
    $address = htmlspecialchars(trim($_POST["address"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $comment = htmlspecialchars(trim($_POST["comment"]));

    // (Optional) Save to a database or a log file
    $log = "Name: $name\nPhone: $phone\nAddress: $address\nEmail: $email\nComment: $comment\n---\n";
    file_put_contents("signup_log.txt", $log, FILE_APPEND);

    // Show thank-you message
    echo "<h2>Thank you, $name, for creating an account.</h2>";
    echo "<p>Kindly be patient as we are working on your request.</p>";
    echo "<a href='signup.html'>← Back to Signup</a>";
} else {
    header("Location: signup.html");
    exit;
}
