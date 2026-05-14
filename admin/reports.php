<?php
session_start();
include(__DIR__ . '/../config/connect.php');
include('risk-engine.php');

if ($_SESSION['role'] != 'admin') exit();

$users = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">
            <h1>Reports & Analytics</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">
                <div class="table-container">

                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Violations</th>
                            <th>Risk</th>
                        </tr>

                        <?php while ($u = mysqli_fetch_assoc($users)) {

                            [$risk, $class] = getRiskLevel($u['id'], $conn);

                            $count = mysqli_num_rows(mysqli_query($conn, "
SELECT * FROM violations WHERE user_id='{$u['id']}'
"));
                        ?>

                            <tr>
                                <td><?= $u['username'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><?= $count ?></td>
                                <td><?= $risk ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>
            </div>
        </div>

</body>

</html>