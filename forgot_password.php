<?php
include "db.php";

$message = "";

if (isset($_POST['reset'])) {
    $username = trim($_POST['username']);

    // Check if user exists
    $check = mysqli_query($conn,
        "SELECT id FROM users WHERE username='$username' LIMIT 1"
    );

    if (mysqli_num_rows($check) === 1) {
        // Reset password to default
        $newPassword = "123456";
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE users SET password='$hashedPassword' WHERE username='$username'"
        );

        $message = "Password reset successful. New password: 123456 (change after login)";
    } else {
        $message = "Username not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Forgot Password</h2>

        <?php if ($message): ?>
            <div class="error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <button name="reset">Reset Password</button>
        </form>

        <p style="margin-top:15px;">
            <a href="login.php">Back to Login</a>
        </p>
    </div>
</div>

</body>
</html>
