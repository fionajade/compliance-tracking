<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* VIOLATIONS DATA */
$violations = mysqli_query($conn, "SELECT * FROM violations ORDER BY id DESC");
$totalViolations = mysqli_num_rows($violations);
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <h1>⚠️ Violations Overview</h1>
        <p style="opacity:0.7;">
            Policy breaches and compliance violations tracked by employee activity
        </p>

        <!-- SUMMARY -->
        <div class="cards" style="margin-top:20px;">
            <div class="card glass">
                <h3>Total Violations</h3>
                <p><?= $totalViolations ?></p>
            </div>
        </div>

        <!-- TABLE -->
        <div class="glass" style="margin-top:25px; padding:20px; border-radius:15px; overflow-x:auto;">

            <table style="width:100%; color:white; border-collapse:collapse; min-width:700px;">

                <tr style="text-align:left; border-bottom:1px solid rgba(255,255,255,0.2);">
                    <th>Employee ID</th>
                    <th>Title</th>
                    <th>Severity</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($violations)) { ?>

                <tr style="border-bottom:1px solid rgba(255,255,255,0.1);">

                    <!-- EMPLOYEE ID (REPLACES INTERNAL ID) -->
                    <td>
                        <?= isset($row['user_id']) ? "EMP-" . $row['user_id'] : 'N/A' ?>
                    </td>

                    <!-- TITLE / DESCRIPTION -->
                    <td>
                        <?= isset($row['title'])
                            ? $row['title']
                            : (isset($row['description']) ? $row['description'] : 'No Title') ?>
                    </td>

                    <!-- SEVERITY -->
                    <td style="color:#ffcc00;">
                        <?= isset($row['severity']) ? $row['severity'] : 'Unknown' ?>
                    </td>

                    <!-- STATUS -->
                    <td>
                        <?= isset($row['status']) ? $row['status'] : 'Pending' ?>
                    </td>

                </tr>

                <?php } ?>

            </table>

        </div>

    </div>
</div>

</body>
</html>