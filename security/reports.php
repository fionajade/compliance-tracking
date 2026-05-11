<?php
include(__DIR__ . '/../config/connect.php');

$reports = mysqli_query($conn, "SELECT * FROM incident_reports");
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container dashboard">

    <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>

    <div class="main-content glass">

        <h1>Reports</h1>

        <div class="glass" style="padding:20px;margin-top:20px;">
            <div class="table-container">
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($r = mysqli_fetch_assoc($reports)) { ?>

                        <tr>
                            <td><?php echo $r['title']; ?></td>
                            <td><?php echo $r['severity']; ?></td>
                            <td><?php echo $r['status']; ?></td>
                            <td><?php echo $r['date_reported']; ?></td>
                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>
    </div>