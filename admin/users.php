<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* =========================
   ACTIONS
========================= */

// LOCK
if (isset($_GET['lock'])) {

    $id = $_GET['lock'];

    mysqli_query($conn, "
        UPDATE users
        SET is_locked=1
        WHERE id='$id'
    ");

    header("Location: users.php");
    exit();
}

// UNLOCK
if (isset($_GET['unlock'])) {

    $id = $_GET['unlock'];

    mysqli_query($conn, "
        UPDATE users
        SET is_locked=0,
            login_attempts=0
        WHERE id='$id'
    ");

    header("Location: users.php");
    exit();
}

// RESET PASSWORD
if (isset($_GET['reset'])) {

    $id = $_GET['reset'];

    $result = mysqli_query($conn, "SELECT role FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($result);

    if ($user) {

        $defaultPassword = ($user['role'] == 'admin') ? "admin123" : "employee123";
        $hashed = password_hash($defaultPassword, PASSWORD_DEFAULT);

        mysqli_query($conn, "
            UPDATE users
            SET password='$hashed',
                must_change_password=1
            WHERE id='$id'
        ");
    }

    header("Location: users.php?success=passwordreset");
    exit();
}

// RESET VIOLATION COUNT
if (isset($_GET['reset_violation'])) {

    $id = $_GET['reset_violation'];

    mysqli_query($conn, "
        UPDATE users
        SET login_attempts=0,
            is_locked=0
        WHERE id='$id'
    ");

    header("Location: users.php?success=violationreset");
    exit();
}

// DELETE USER
if (isset($_GET['delete'])) {

    $id = $_GET['delete'];

    // prevent self-delete
    if ($id == $_SESSION['id']) {
        header("Location: users.php?error=cannotdeleteown");
        exit();
    }

    mysqli_query($conn, "
        DELETE FROM users
        WHERE id='$id'
    ");

    header("Location: users.php?success=deleted");
    exit();
}

/* =========================
   USERS LIST
========================= */

$users = mysqli_query($conn, "
    SELECT *
    FROM users
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

<?php include('sidebar.php'); ?>

<div class="main-content glass">

<!-- HEADER -->
<div style="display:flex;justify-content:space-between;align-items:center;">

    <div>
        <h1>👤 User Management</h1>
        <p style="opacity:0.7;">Manage employees, security, and admin accounts</p>
    </div>

    <a href="create-user.php"
       style="background:#00ff99;color:#000;padding:10px 15px;border-radius:8px;text-decoration:none;font-weight:bold;">
        + Create User
    </a>

</div>

<!-- SUCCESS / ERROR MESSAGES -->
<?php if (isset($_GET['success']) && $_GET['success'] == 'passwordreset') { ?>
    <div class="success-box">✔ Password reset successfully!</div>
<?php } ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'violationreset') { ?>
    <div class="success-box">✔ Violation count reset!</div>
<?php } ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'deleted') { ?>
    <div class="success-box">✔ User deleted successfully!</div>
<?php } ?>

<?php if (isset($_GET['error']) && $_GET['error'] == 'cannotdeleteown') { ?>
    <div class="error-box">❌ You cannot delete your own account!</div>
<?php } ?>

<!-- TABLE -->
<div class="glass" style="margin-top:20px;padding:20px;overflow-x:auto;">

<table style="width:100%;color:white;border-collapse:collapse;min-width:1000px;">

<tr style="text-align:left;border-bottom:1px solid rgba(255,255,255,0.2);">
    <th>Employee ID</th>
    <th>Username</th>
    <th>Department</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($users)) { ?>

<tr style="border-bottom:1px solid rgba(255,255,255,0.1);">

    <td><?= $row['employee_id'] ?? 'NOT ASSIGNED' ?></td>
    <td><?= $row['username'] ?></td>
    <td><?= $row['department'] ?? 'N/A' ?></td>
    <td><?= strtoupper($row['role']) ?></td>

    <td>
        <?php if ($row['is_locked']) { ?>
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

        &nbsp;|&nbsp;

        <a href="?reset_violation=<?= $row['id'] ?>" style="color:#ff9900;">Reset Violation</a>

        &nbsp;|&nbsp;

        <a href="edit-users.php?id=<?= $row['id'] ?>" style="color:#00ccff;">Edit</a>

        &nbsp;|&nbsp;

        <a href="?delete=<?= $row['id'] ?>"
           onclick="return confirm('Are you sure you want to delete this user? This cannot be undone!');"
           style="color:#ff4d4d;">
           Delete
        </a>

    </td>

</tr>

<?php } ?>

</table>

</div>

</div>
</div>

</body>
</html>