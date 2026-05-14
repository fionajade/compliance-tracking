<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../index.php");
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

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <h1>📜 Activity Timeline</h1>
        <p style="opacity:0.7; margin-bottom:20px;">
            Track everything you do inside the system.
        </p>

        <div class="timeline">

            <?php if (mysqli_num_rows($query) > 0) { ?>

                <?php while ($row = mysqli_fetch_assoc($query)) { ?>

                    <?php
                        $action = strtolower($row['action']);

                        $color = "#66b3ff";
                        $icon = "📌";

                        if (strpos($action, 'submitted') !== false) {
                            $color = "#00ff99";
                            $icon = "📩";
                        } elseif (strpos($action, 'updated') !== false) {
                            $color = "#ffcc00";
                            $icon = "🔄";
                        } elseif (strpos($action, 'violation') !== false) {
                            $color = "#ff4d4d";
                            $icon = "⚠️";
                        } elseif (strpos($action, 'task') !== false) {
                            $color = "#9b59b6";
                            $icon = "📋";
                        }
                    ?>

                    <div class="timeline-item glass" style="margin-bottom:15px; padding:15px; position:relative;">

                        <!-- DOT -->
                        <div style="
                            width:10px;
                            height:10px;
                            background:<?= $color ?>;
                            border-radius:50%;
                            position:absolute;
                            left:-5px;
                            top:20px;
                        "></div>

                        <div class="content">

                            <!-- ACTION -->
                            <h3 style="margin:0; font-size:16px; color:<?= $color ?>;">
                                <?= $icon ?> <?= htmlspecialchars($row['action']) ?>
                            </h3>

                            <!-- META -->
                            <small style="opacity:0.6;">
                                🕒 <?= $row['log_time'] ?>

                                <?php if (!empty($row['task_id'])) { ?>
                                    | 📋 Task #<?= $row['task_id'] ?>
                                <?php } ?>
                            </small>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <p style="opacity:0.6;">No activity found.</p>

            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>