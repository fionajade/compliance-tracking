<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') exit();

$policies = mysqli_query($conn, "SELECT * FROM policies");
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>


        <div class="main-content glass">
            <h1>Policy Management</h1>

            <div class="glass" style="padding:20px; margin-top:20px;">
                <div class="table-container">

                    <table>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>

                        <?php while ($p = mysqli_fetch_assoc($policies)) { ?>
                            <tr>
                                <td><?= $p['policy_name'] ?></td>
                                <td><?= $p['description'] ?></td>
                                <td><?= $p['status'] ?></td>
                            </tr>
                        <?php } ?>

                    </table>

                </div>
            </div>
        </div>

</body>

</html>