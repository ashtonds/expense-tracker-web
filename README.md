# 💰 Expense Tracker Web Application

A full-stack expense tracker built for students to manage income and expenses with authentication, charts, and monthly insights.

---

## 🚀 Features
- User registration & login
- Secure password hashing
- Role-based access control
- Add, edit, delete transactions (Full CRUD)
- Category-wise expense tracking
- Monthly filtering
- Interactive charts using Chart.js
- Responsive dashboard UI

---

## 🛠 Tech Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Charts:** Chart.js
- **Auth:** PHP Sessions + password_hash()

---

## 📊 Screenshots
- Login Page  
- Register Page  
- Add Transaction Page  
- Dashboard with Charts  
- Edit Transaction Page  

---

## 📂 Project Structure
ExpenseWebsite/
│
├── db.php
├── login.php
├── register.php
├── forgot_password.php
├── change_password.php
├── logout.php
├── index.php
├── dashboard.php
├── edit.php
├── style.css
├── login.css
└── README.md


---

## ▶ How to Run Locally
1. Install XAMPP
2. Start Apache & MySQL
3. Import database SQL
4. Place project in `htdocs`
5. Open `http://localhost/ExpenseWebsite/login.php`

---

## 🧠 Learning Outcomes
- CRUD operations
- SQL aggregation & filtering
- Session management
- Secure authentication
- Data visualization
- Responsive UI design

---

## 📌 Interview Highlight
> “I built a secure expense tracking web application with authentication, full CRUD operations, monthly analytics, and interactive data visualization.”
### 🔐 PREPARED STATEMENTS (SECURITY UPGRADE)

❌ Old (unsafe)

 $sql = "SELECT * FROM users WHERE username='$username'";

✅ New (SAFE)

$stmt = mysqli_prepare($conn,"SELECT * FROM users WHERE username=? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);


🔄 Example:

Insert Transaction (Secure)
$stmt = mysqli_prepare($conn,
    "INSERT INTO transactions (type, category, amount, description, created_at)
     VALUES (?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssdss",
    $type,
    $category,
    $amount,
    $description,
    $date
);

mysqli_stmt_execute($stmt);
