<?php
include "db.php";

$message = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username already exists
    $check = mysqli_query($conn,
        "SELECT id FROM users WHERE username='$username' LIMIT 1"
    );

    if (mysqli_num_rows($check) > 0) {
        $message = "Username already exists";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $role = "user";

        mysqli_query($conn,
            "INSERT INTO users (username, password, role)
             VALUES ('$username', '$hashedPassword', '$role')"
        );

        $message = "Registration successful. You can login now.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Create Account</h2>

        <?php if ($message): ?>
            <div class="error"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button name="register">Register</button>
        </form>

        <p style="margin-top:15px;">
            <a href="login.php">Back to Login</a>
        </p>
    </div>
</div>

</body>
</html>
