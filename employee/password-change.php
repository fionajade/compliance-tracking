<?php
session_start();
include(__DIR__ . '/../config/connect.php');

$user_id = $_SESSION['id'];

if (isset($_POST['update'])) {

    $new = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "
        UPDATE users
        SET password='$new', must_change_password=0
        WHERE id='$user_id'
    ");

    header("Location: index.php");
    exit();
}
?>

<h2>Change Your Password</h2>

<form method="POST">
    <input type="password" name="new_password" placeholder="New Password" required>
    <button type="submit" name="update">Update</button>
</form>