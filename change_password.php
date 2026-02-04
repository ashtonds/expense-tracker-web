<?php
session_start();
include "db.php";

// Must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$message = "";
$username = $_SESSION['user'];

if (isset($_POST['change'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];

    // Fetch current user
    $result = mysqli_query($conn,
        "SELECT password FROM users WHERE username='$username' LIMIT 1"
    );
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($current, $user['password'])) {
        $newHash = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query($conn,
            "UPDATE users SET password='$newHash' WHERE username='$username'"
        );

        $message = "Password changed successfully";
    } else {
        $message = "Current password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Change Password</h2>

        <?php if ($message): ?>
            <div class="error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>

            <button name="change">Update Password</button>
        </form>

        <p style="margin-top:15px;">
            <a href="dashboard.php">Back to Dashboard</a>
        </p>
    </div>
</div>

</body>
</html>
