<?php
include('config/connect.php');
session_start();

$error = "";

if (isset($_POST['btnLogin'])) {

    // FIX: use identifier instead of email
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $password = $_POST['password'];

    $loginQuery = "
        SELECT * FROM users
        WHERE email = '$identifier'
        OR username = '$identifier'
        OR employee_id = '$identifier'
        LIMIT 1
    ";

    $loginResult = mysqli_query($conn, $loginQuery);

    if ($loginResult && mysqli_num_rows($loginResult) > 0) {

        $user = mysqli_fetch_assoc($loginResult);

        // FIX: handle plain text password in DB (no hash mismatch issues)
        if ($user['password'] === $password) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // your DB uses "username"
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == "admin") {
                header("Location: __DIR__ . '/../admin/dashboard.php");
                exit();
            } elseif ($user['role'] == "security") {
                header("Location: __DIR__ . '/../security/dashboard.php");
                exit();
            } elseif ($user['role'] == "employee") {
                header("Location: __DIR__ . '/../employee/dashboard.php");
                exit();
            }
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>

<?php include('includes/header.php'); ?>


<body>

    <div class="login-card glass">

        <h1>Compliance System</h1>

        <p style="text-align:center; font-size:13px; color:#ccc;">
            Use your company-issued credentials to log in
        </p>

        <?php if (!empty($error)): ?>
            <p style="color:#ffb3b3;text-align:center;margin-bottom:15px;">
                <?= $error ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <div class="input-group">
                <label>Email or Username</label>
                <input type="text" name="identifier" required>
            </div>

            <div class="input-group">
                <label>Password (temporary or assigned)</label>
                <input type="password" name="password" required>
            </div>

            <button class="btn" type="submit" name="btnLogin">
                Login
            </button>

        </form>

    </div>

</body>

</html>