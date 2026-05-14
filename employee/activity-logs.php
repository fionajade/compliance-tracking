<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['id'];

$query = mysqli_query($conn, "
SELECT * FROM activity_logs
WHERE user_id='$userID'
ORDER BY log_time DESC
");
?>


<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>


<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

        <div class="main-content glass">

            <h1>📜 Activity Timeline</h1>

            <p style="opacity:0.7; margin-bottom:20px;">
                Track all your actions in the system.
            </p>

            <div class="timeline">

                <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                    <div class="timeline-item glass">

                        <div class="dot"></div>

                        <div class="content">
                            <h3><?= $row['action'] ?></h3>
                            <small><?= $row['log_time'] ?></small>
                        </div>

                    </div>

                <?php } ?>

            </div>

        </div>
    </div>

</body>

</html>