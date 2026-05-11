<?php
include(__DIR__ . '/../config/connect.php');

$logs = mysqli_query($conn, "SELECT activity_logs.*, users.fullname
FROM activity_logs
INNER JOIN users ON activity_logs.user_id = users.id
ORDER BY log_time DESC");
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container dashboard">

    <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>

    <div class="main-content glass">

        <h1>Audit Logs</h1>

        <div class="glass" style="padding:20px;margin-top:20px;">
            <div class="table-container">
                <table>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($l = mysqli_fetch_assoc($logs)) { ?>

                        <tr>
                            <td><?php echo $l['fullname']; ?></td>
                            <td><?php echo $l['action']; ?></td>
                            <td><?php echo $l['log_time']; ?></td>
                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>
    </div>