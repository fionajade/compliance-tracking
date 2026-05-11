<?php
include('config/connect.php');
session_start();

$error = "";

if (isset($_POST['btnLogin'])) {

    $email = str_replace("'", "", $_POST['email']);
    $password = md5($_POST['password']);

    $loginQuery = "
        SELECT * FROM users
        WHERE email = '$email'
        AND password = '$password'
    ";

    $loginResult = mysqli_query($conn, $loginQuery);

    if (mysqli_num_rows($loginResult) > 0) {

        while ($user = mysqli_fetch_assoc($loginResult)) {

            $_SESSION['id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
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

            } else {

                $error = "Invalid role";

            }
        }


    } else {

        $error = "Invalid email or password";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-card glass">

    <h1>Compliance System</h1>

    <?php
    if ($error != "") {
        echo "<p style='color:#ffb3b3; text-align:center; margin-bottom:15px;'>$error</p>";
    }
    ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" type="submit" name="btnLogin">
            Login
        </button>

    </form>

</div>

</body>
</html>