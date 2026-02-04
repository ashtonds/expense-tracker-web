<?php
session_start();
include "db.php";

// Must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Monthly filter
$monthCondition = "";
if (!empty($_GET['month'])) {
    $month = (int) $_GET['month'];
    $monthCondition = "AND MONTH(created_at) = $month";
}

// Totals
$income = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(amount) AS total FROM transactions 
     WHERE type='income' $monthCondition"
))['total'] ?? 0;

$expense = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(amount) AS total FROM transactions 
     WHERE type='expense' $monthCondition"
))['total'] ?? 0;

$balance = $income - $expense;

// Category-wise expense
$catResult = mysqli_query($conn,
    "SELECT category, SUM(amount) AS total FROM transactions
     WHERE type='expense' $monthCondition
     GROUP BY category"
);

$categories = [];
$amounts = [];

while ($row = mysqli_fetch_assoc($catResult)) {
    $categories[] = $row['category'];
    $amounts[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Expense Dashboard</h2>

<div style="text-align:center;">
    <a href="index.php" class="dashboard-btn">Add Transaction</a>
    <a href="change_password.php" class="dashboard-btn">Change Password</a>
    <a href="logout.php" class="dashboard-btn">Logout</a>
</div>

<!-- Month Filter -->
<form method="GET" style="text-align:center; margin-top:20px;">
    <select name="month">
        <option value="">All Months</option>
        <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= (!empty($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '' ?>>
                <?= date('F', mktime(0,0,0,$m,1)) ?>
            </option>
        <?php endfor; ?>
    </select>
    <button>Filter</button>
</form>

<!-- Summary Cards -->
<div class="cards">
    <div class="card income">Income<br>₹<?= $income ?></div>
    <div class="card expense">Expense<br>₹<?= $expense ?></div>
    <div class="card balance">Balance<br>₹<?= $balance ?></div>
</div>

<!-- Charts -->
<div class="charts-row">
    <div class="chart-box">
        <h4>Expense by Category</h4>
        <canvas id="expenseChart"></canvas>
    </div>

    <div class="chart-box">
        <h4>Income vs Expense</h4>
        <canvas id="summaryChart"></canvas>
    </div>
</div>

<!-- Transactions Table -->
<div class="table-container">
    <table>
        <tr>
            <th>Type</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $result = mysqli_query($conn,
            "SELECT * FROM transactions 
             WHERE 1=1 $monthCondition
             ORDER BY created_at DESC"
        );

        while ($row = mysqli_fetch_assoc($result)):
        ?>
        <tr>
            <td><?= ucfirst($row['type']) ?></td>
            <td><?= $row['category'] ?></td>
            <td>₹<?= $row['amount'] ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $row['id'] ?>"
                   onclick="return confirm('Delete this transaction?')">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Charts Script -->
<script>
new Chart(document.getElementById('expenseChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($categories) ?>,
        datasets: [{
            data: <?= json_encode($amounts) ?>,
            borderWidth: 1
        }]
    }
});

new Chart(document.getElementById('summaryChart'), {
    type: 'bar',
    data: {
        labels: ['Income', 'Expense', 'Balance'],
        datasets: [{
            data: [<?= $income ?>, <?= $expense ?>, <?= $balance ?>],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
