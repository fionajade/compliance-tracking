<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

if (isset($_POST['create'])) {

    $name = $_POST['name'];
    $username = $_POST['username'];
    $department = $_POST['department'];
    $role = $_POST['role'];
    $temp_password = $_POST['password'];

    // 🔢 Generate Employee ID
    $countQuery = mysqli_query($conn, "
        SELECT COUNT(*) as total
        FROM users
        WHERE department='$department'
    ");

    $row = mysqli_fetch_assoc($countQuery);
    $next = $row['total'] + 1;

    $employee_id = strtoupper($department) . "-EMP-" . str_pad($next, 4, "0", STR_PAD_LEFT);

    // 🔐 Hash password
    $hashed = password_hash($temp_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
    (employee_id, username, name, password, role, department, must_change_password)
    VALUES
    ('$employee_id','$username','$name','$hashed','$role','$department',1)";

    if (mysqli_query($conn, $sql)) {
        $message = "User created successfully: $employee_id";
    } else {
        $message = "Error creating user.";
    }
}
?>

<?php include("__DIR__ . '/../includes/header.php"); ?>

<body>

<div class="container dashboard">

<?php include('sidebar.php'); ?>

<div class="main-content glass">

<h1>Create Employee Account</h1>
<p style="opacity:0.7;">HR onboarding system</p>

<?php if ($message) { echo "<p>$message</p>"; } ?>

<form method="POST">

    <input type="text" name="name" placeholder="Full Name" required><br><br>

    <input type="text" name="username" placeholder="Username" required><br><br>

    <select name="department" required>
        <option value="IT">IT</option>
        <option value="HR">HR</option>
        <option value="SEC">Security</option>
    </select><br><br>

    <select name="role" required>
        <option value="employee">Employee</option>
        <option value="security">Security</option>
        <option value="admin">Admin</option>
    </select><br><br>

    <input type="password" name="password" placeholder="Temporary Password" required><br><br>

    <button type="submit" name="create">Create User</button>

</form>

</div>
</div>

</body>
</html>