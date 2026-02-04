<?php
session_start();
include "db.php";

// Must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$message = "";

if (isset($_POST['add'])) {
    $type        = $_POST['type'];
    $category    = $_POST['category'];
    $amount      = $_POST['amount'];
    $description = $_POST['description'];
    $date        = $_POST['date'];
    $username    = $_SESSION['user'];

    if ($amount > 0) {
        mysqli_query($conn,
            "INSERT INTO transactions 
            (type, category, amount, description, created_at)
            VALUES 
            ('$type', '$category', '$amount', '$description', '$date')"
        );

        $message = "Transaction added successfully";
    } else {
        $message = "Amount must be greater than zero";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Transaction</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Add Income / Expense</h2>

<form method="POST">
    <select name="type" required>
        <option value="">Select Type</option>
        <option value="income">Income</option>
        <option value="expense">Expense</option>
    </select>

    <select name="category" required>
        <option>Food</option>
        <option>Travel</option>
        <option>Rent</option>
        <option>Entertainment</option>
        <option>Other</option>
    </select>

    <input type="number" name="amount" placeholder="Amount" required>
    <input type="text" name="description" placeholder="Description">
    <input type="date" name="date" required>

    <button name="add">Add Transaction</button>
</form>

<?php if ($message): ?>
    <p style="text-align:center; color:white;"><?= $message ?></p>
<?php endif; ?>

<a href="dashboard.php" class="dashboard-btn">View Dashboard</a>

</body>
</html>
