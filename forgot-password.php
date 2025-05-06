<?php
$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $newPassword = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit;
    }

    // Load tokens
    $lines = file("reset_tokens.txt", FILE_IGNORE_NEW_LINES);
    $valid = false;
    $email = "";

    foreach ($lines as $line) {
        list($savedEmail, $savedToken, $expires) = explode("|", $line);
        if (trim($savedToken) === $token && time() < $expires) {
            $valid = true;
            $email = $savedEmail;
            break;
        }
    }

    if (!$valid) {
        echo "<script>alert('Invalid or expired token.'); window.location.href = 'forgot-password.html';</script>";
        exit;
    }

    // Here you’d save the new password to your database
    $entry = "Password reset for $email at " . date("Y-m-d H:i:s") . ". New Password: " . $newPassword . "\n";
    file_put_contents("password_resets.log", $entry, FILE_APPEND);

    echo "<script>
      alert('Your password has been successfully reset!');
      window.location.href = 'login.html';
    </script>";
    exit;
}

// Database connection
$pdo = new PDO('mysql:host=localhost;dbname=vision', 'root', '');


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');history.back();</script>";
        exit;
    }

    // Generate secure token
    $token = bin2hex(random_bytes(32));

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $token]);

    // Normally you would email this
    $resetLink = "http://kellyovvy@gmail.com/reset-password.php?token=$token";

    // Simulate email
    echo "<script>
        alert('A reset link has been sent: $resetLink');
        window.location.href = 'login.html';
    </script>";}
    ?>
    
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Password | VISION KreationS</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <section class="reset-password-section">
    <div class="form-container">
      <h2>Reset Your Password</h2>
      <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
        <input type="password" name="password" placeholder="New password" required />
        <input type="password" name="confirm_password" placeholder="Confirm new password" required />
        <button type="submit">Reset Password</button>
      </form>
      <p><a href="login.html">Back to login</a></p>
    </div>
  </section>
</body>
</html>
