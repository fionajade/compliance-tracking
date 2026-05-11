<?php
include(__DIR__ . '/../config/connect.php');

$incidents = mysqli_query($conn, "SELECT * FROM incident_reports ORDER BY date_reported DESC");
?>

<link rel="stylesheet" href="../css/style.css">
<div class="container dashboard">

    <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>

    <div class="main-content glass">

        <h1>Incident Monitoring</h1>

        <div class="glass" style="padding:20px;margin-top:20px;">
            <div class="table-container">
                <table>
                    <tr>
                        <th>Title</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($i = mysqli_fetch_assoc($incidents)) { ?>

                        <tr>
                            <td><?php echo $i['title']; ?></td>
                            <td><?php echo $i['severity']; ?></td>
                            <td><?php echo $i['status']; ?></td>
                            <td><?php echo $i['date_reported']; ?></td>
                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>
    </div>