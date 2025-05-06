<?php
$pdo = new PDO('mysql:host=localhost;dbname=vision', 'root', '');

$token = $_GET['token'] ?? '';

// Check if token exists
$stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = ?");
$stmt->execute([$token]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    die("Invalid or expired token.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST['password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in users table
    $update = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->execute([$hashedPassword, $row['email']]);

    // Delete the token
    $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$token]);

    echo "<script>alert('Password successfully updated. Please login.');window.location='login.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <section class="reset-password-section">
    <div class="form-container">
      <h2>Reset Your Password</h2>
      <form method="POST">
        <input type="password" name="password" placeholder="New Password" required />
        <button type="submit">Update Password</button>
      </form>
    </div>
  </section>
</body>
</html>
