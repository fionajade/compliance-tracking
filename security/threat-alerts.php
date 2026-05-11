<?php
include(__DIR__ . '/../config/connect.php');

$violations = mysqli_query($conn, "SELECT violations.*, users.fullname
FROM violations
INNER JOIN users ON violations.user_id = users.id
ORDER BY created_at DESC");
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container dashboard">

    <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>
    <div class="main-content glass">

        <h1>Threat Alerts</h1>

        <div class="glass" style="padding:20px;margin-top:20px;">
            <div class="table-container">
                <table>
                    <tr>
                        <th>User</th>
                        <th>Type</th>
                        <th>Severity</th>
                        <th>Status</th>
                    </tr>

                    <?php while ($v = mysqli_fetch_assoc($violations)) { ?>

                        <tr>
                            <td><?php echo $v['fullname']; ?></td>
                            <td><?php echo $v['violation_type']; ?></td>
                            <td><?php echo $v['severity']; ?></td>
                            <td><?php echo $v['status']; ?></td>
                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>
    </div>