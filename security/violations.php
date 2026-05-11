<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header("Location: ../index.php");
    exit();
}

$name = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Threat Alerts / Violations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>

        <!-- MAIN CONTENT -->
        <div class="main-content glass">

            <h1>Threat Alerts & Violations</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">

                <div class="table-container">

                    <table>

                        <tr>
                            <th>User</th>
                            <th>Violation Type</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        $query = mysqli_query($conn, "
                    SELECT violations.*, users.fullname
                    FROM violations
                    INNER JOIN users ON violations.user_id = users.id
                    ORDER BY created_at DESC
                ");

                        while ($v = mysqli_fetch_assoc($query)) {

                            /* =========================
                       AUTO SEVERITY COLOR
                    ========================= */

                            if ($v['severity'] == "High") {
                                $severityClass = "badge-high";
                            } elseif ($v['severity'] == "Medium") {
                                $severityClass = "badge-medium";
                            } else {
                                $severityClass = "badge-low";
                            }

                            /* =========================
                       STATUS COLOR
                    ========================= */

                            if ($v['status'] == "Open") {
                                $statusClass = "badge-high";
                            } else {
                                $statusClass = "badge-low";
                            }
                        ?>

                            <tr>
                                <td><?php echo $v['fullname']; ?></td>
                                <td><?php echo $v['violation_type']; ?></td>

                                <td>
                                    <span class="badge <?php echo $severityClass; ?>">
                                        <?php echo $v['severity']; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $v['status']; ?>
                                    </span>
                                </td>

                                <td>
                                    <?php echo $v['created_at']; ?>
                                </td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </div>
    </div>

</body>

</html>