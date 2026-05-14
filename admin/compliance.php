<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$data = mysqli_query($conn, "
SELECT compliance_records.*, users.username, policies.policy_name
FROM compliance_records
INNER JOIN users ON compliance_records.user_id = users.id
INNER JOIN policies ON compliance_records.policy_id = policies.id
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>


        <div class="main-content glass">

            <h1>Compliance Monitoring</h1>

            <div class="glass" style="padding:20px; margin-top:20px;">
                <div class="table-container">

                    <table>

                        <tr>
                            <th>Employee</th>
                            <th>Policy</th>
                            <th>Status</th>
                            <th>Updated</th>
                        </tr>

                        <?php while ($r = mysqli_fetch_assoc($data)) { ?>

                            <tr>

                                <td><?= $r['username'] ?></td>
                                <td><?= $r['policy_name'] ?></td>

                                <td>
                                    <?php
                                    if ($r['compliance_status'] == "Compliant") {
                                        echo "<span class='badge badge-low'>Compliant</span>";
                                    } elseif ($r['compliance_status'] == "Pending") {
                                        echo "<span class='badge badge-medium'>Pending</span>";
                                    } else {
                                        echo "<span class='badge badge-high'>Non-Compliant</span>";
                                    }
                                    ?>
                                </td>

                                <td><?= $r['updated_at'] ?></td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </div>

</body>

</html>