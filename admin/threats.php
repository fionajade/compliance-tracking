<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* INCIDENT DATA */
$incidents = mysqli_query($conn, "SELECT * FROM incident_reports ORDER BY date_reported DESC");
$totalIncidents = mysqli_num_rows($incidents);
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <h1>🚨 Threat Alerts (Incidents)</h1>
        <p style="opacity:0.7;">All reported security incidents and system threats</p>

        <!-- SUMMARY CARD -->
        <div class="cards" style="margin-top:20px;">
            <div class="card glass">
                <h3>Total Incidents</h3>
                <p><?= $totalIncidents ?></p>
            </div>
        </div>

        <!-- INCIDENT LIST -->
        <div class="glass" style="margin-top:25px; padding:20px; border-radius:15px;">

            <table style="width:100%; color:white; border-collapse:collapse;">
                <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.2);">
                    <th>ID</th>
                    <th>Title</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Date Reported</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($incidents)) { ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td style="color:#ff4d4d;"><?= $row['severity'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['date_reported'] ?></td>
                    </tr>
                <?php } ?>

            </table>

        </div>

    </div>
</div>

</body>
</html>