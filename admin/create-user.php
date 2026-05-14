<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

/* =========================
   CREATE USER (NO HASH)
========================= */
if (isset($_POST['create'])) {

    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, $_POST['username']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $contact_number = isset($_POST['contact_number']) ? mysqli_real_escape_string($conn, $_POST['contact_number']) : '';
    $department = isset($_POST['department']) ? mysqli_real_escape_string($conn, $_POST['department']) : '';
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    // ✅ DEFAULT PASSWORD (NO INPUT NEEDED)
    $temp_password = "password123";

    // INSERT USER
    mysqli_query($conn, "
        INSERT INTO users (
            username,
            email,
            contact_number,
            password,
            role,
            department,
            is_locked,
            login_attempts,
            must_change_password
        )
        VALUES (
            '$username',
            '$email',
            '$contact_number',
            '$temp_password',
            '$role',
            '$department',
            0,
            0,
            1
        )
    ");

    $last_id = mysqli_insert_id($conn);

    $employee_id = strtoupper($department) . "-EMP-" . str_pad($last_id, 5, "0", STR_PAD_LEFT);

    mysqli_query($conn, "
        UPDATE users
        SET employee_id='$employee_id'
        WHERE id='$last_id'
    ");

    $message = "✔ User created successfully! Default password: password123 | Employee ID: $employee_id";
}
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>Create Employee Account</h1>
            <p style="opacity:0.7;">HR onboarding system</p>

            <!-- SUCCESS MESSAGE -->
            <?php if ($message) { ?>
                <div class="success-box" style="margin:10px 0;">
                    <?= $message ?>
                </div>
            <?php } ?>

            <!-- FORM -->
            <form method="POST">

                <input type="text" name="username" placeholder="Full Name" required><br><br>


                <input type="email" name="email" placeholder="Email Address" required><br><br>

                <input type="text" name="contact_number" placeholder="Contact Number" required><br><br>

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

                <button type="submit" name="create"
                    style="background:#00ff99;padding:10px;border:none;">
                    Create User
                </button>

            </form>

        </div>
    </div>

</body>

</html>