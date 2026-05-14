<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') exit();

$data = mysqli_query($conn, "
SELECT activity_logs.*, users.username
FROM activity_logs
INNER JOIN users ON users.id = activity_logs.user_id
ORDER BY log_time DESC
");
?>

<!DOCTYPE html>
<html>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">
            <h1>Audit Logs</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">
                <div class="table-container">

                    <table>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Task ID</th>
                            <th>Date</th>
                        </tr>

                        <?php while ($l = mysqli_fetch_assoc($data)) { ?>

                            <tr>
                                <td><?= $l['username'] ?></td>
                                <td><?= $l['action'] ?></td>
                                <td><?= $l['task_id'] ?></td>
                                <td><?= $l['log_time'] ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>
            </div>
        </div>

</body>

</html>