<?php
session_start();
include "db.php";

// Must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Validate ID
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int) $_GET['id'];

// Fetch transaction
$result = mysqli_query($conn,
    "SELECT * FROM transactions WHERE id=$id LIMIT 1"
);
$transaction = mysqli_fetch_assoc($result);

if (!$transaction) {
    header("Location: dashboard.php");
    exit;
}

// Update transaction
if (isset($_POST['update'])) {
    $type        = $_POST['type'];
    $category    = $_POST['category'];
    $amount      = $_POST['amount'];
    $description = $_POST['description'];
    $date        = $_POST['date'];

    if ($amount > 0) {
        mysqli_query($conn,
            "UPDATE transactions SET
                type='$type',
                category='$category',
                amount='$amount',
                description='$description',
                created_at='$date'
             WHERE id=$id"
        );

        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Transaction</h2>

<form method="POST">
    <select name="type" required>
        <option value="income" <?= $transaction['type'] == 'income' ? 'selected' : '' ?>>Income</option>
        <option value="expense" <?= $transaction['type'] == 'expense' ? 'selected' : '' ?>>Expense</option>
    </select>

    <select name="category" required>
        <?php
        $categories = ['Food','Travel','Rent','Entertainment','Other'];
        foreach ($categories as $cat):
        ?>
            <option value="<?= $cat ?>" <?= $transaction['category'] == $cat ? 'selected' : '' ?>>
                <?= $cat ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="number" name="amount" value="<?= $transaction['amount'] ?>" required>
    <input type="text" name="description" value="<?= $transaction['description'] ?>">
    <input type="date" name="date" value="<?= $transaction['created_at'] ?>" required>

    <button name="update">Update Transaction</button>
</form>

<a href="dashboard.php" class="dashboard-btn">Back to Dashboard</a>

</body>
</html>
