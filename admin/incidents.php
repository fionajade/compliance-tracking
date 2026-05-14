<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') exit();

$data = mysqli_query($conn, "
SELECT incident_reports.*, users.username
FROM incident_reports
INNER JOIN users ON users.id = incident_reports.user_id
ORDER BY date_reported DESC
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>
<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>


        <div class="main-content glass">
            <h1>Incident Response Center</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">
                <div class="table-container">

                    <table>
                        <tr>
                            <th>User</th>
                            <th>Title</th>
                            <th>Severity</th>
                            <th>Status</th>
                        </tr>

                        <?php while ($i = mysqli_fetch_assoc($data)) { ?>

                            <tr>
                                <td><?= $i['username'] ?></td>
                                <td><?= $i['title'] ?></td>
                                <td><?= $i['severity'] ?></td>
                                <td><?= $i['status'] ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>
            </div>
        </div>

</body>

</html>