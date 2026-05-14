<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* ACTIONS */

// LOCK USER
if (isset($_GET['lock'])) {
    $id = $_GET['lock'];
    mysqli_query($conn, "UPDATE users SET is_locked=1 WHERE id=$id");
    header("Location: users.php");
    exit();
}

// UNLOCK USER
if (isset($_GET['unlock'])) {
    $id = $_GET['unlock'];
    mysqli_query($conn, "UPDATE users SET is_locked=0, login_attempts=0 WHERE id=$id");
    header("Location: users.php");
    exit();
}

// RESET PASSWORD
if (isset($_GET['reset'])) {
    $id = $_GET['reset'];

    $temp = md5("1234");

    mysqli_query($conn, "
        UPDATE users
        SET password='$temp', must_change_password=1
        WHERE id=$id
    ");

    header("Location: users.php");
    exit();
}

/* USERS */
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>


<body>

<div class="container dashboard">

<?php include('sidebar.php'); ?>

<div class="main-content glass">

    <!-- HEADER ROW -->
    <div style="display:flex; justify-content:space-between; align-items:center;">

        <div>
            <h1>👤 User Management</h1>
            <p style="opacity:0.7; margin-top:5px;">
                Manage employees, security, and admin accounts
            </p>
        </div>

        <!-- CREATE USER BUTTON -->
        <a href="create-user.php"
           style="
                background:#00ff99;
                color:#000;
                padding:10px 15px;
                border-radius:8px;
                text-decoration:none;
                font-weight:bold;
                height:fit-content;
           ">
            + Create User
        </a>

    </div>

    <!-- TABLE -->
    <div class="glass" style="margin-top:20px; padding:20px; overflow-x:auto;">

        <table style="width:100%; color:white; border-collapse:collapse; min-width:900px;">

            <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.2);">
                <th>Employee ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($users)) { ?>

            <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">

                <td>
                    <?= $row['employee_id'] ? $row['employee_id'] : 'NOT ASSIGNED' ?>
                </td>

                <td><?= $row['username'] ?></td>
                <td><?= $row['email'] ?></td>

                <td><?= $row['department'] ? $row['department'] : 'N/A' ?></td>

                <td><?= strtoupper($row['role']) ?></td>

                <td>
                    <?php if ($row['is_locked'] == 1) { ?>
                        <span style="color:#ff4d4d;">LOCKED</span>
                    <?php } else { ?>
                        <span style="color:#00ff99;">ACTIVE</span>
                    <?php } ?>
                </td>

                <td style="white-space:nowrap;">

                    <?php if ($row['is_locked'] == 0) { ?>
                        <a href="?lock=<?= $row['id'] ?>" style="color:#ffcc00;">Lock</a>
                    <?php } else { ?>
                        <a href="?unlock=<?= $row['id'] ?>" style="color:#00ff99;">Unlock</a>
                    <?php } ?>

                    &nbsp;|&nbsp;

                    <a href="?reset=<?= $row['id'] ?>" style="color:#ff4d4d;">Reset PW</a>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>
</div>

</body>
</html>