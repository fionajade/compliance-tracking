<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($query);

if (isset($_POST['update_account'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn, "
            UPDATE users
            SET username='$username', password='$hashedPassword'
            WHERE id='$user_id'
        ");
    } else {
        mysqli_query($conn, "
            UPDATE users
            SET username='$username'
            WHERE id='$user_id'
        ");
    }

    header("Location: edit_account.php?success=1");
    exit();
}
?>


<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>


<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>👤 My Profile</h1>

            <p style="opacity:0.7; margin-bottom:20px;">
                Manage your account information and security settings.
            </p>

            <?php if (isset($_GET['success'])) { ?>
                <div class="success-box">
                    ✔ Account updated successfully!
                </div>
            <?php } ?>

            <!-- PROFILE CARD -->
            <div class="profile-card glass">

                <div class="avatar">
                    👤
                </div>

                <form method="POST">

                    <div class="input-group">
                        <label>Fullname</label>
                        <input type="text" name="username" value="<?= $user['username'] ?>" required>
                    </div>

                    <div class="input-group">
                        <label>New Password</label>
                        <input type="password" name="password" placeholder="Enter new password">
                    </div>

                    <button class="btn full" type="submit" name="update_account">
                        Save Changes
                    </button>

                </form>

            </div>

        </div>
    </div>

</body>

</html>