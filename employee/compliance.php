<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../index.php");
    exit();
}

$userID = $_SESSION['id'];
$name = $_SESSION['fullname'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Compliance</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

        <div class="main-content glass">

            <h1>My Compliance Records</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">

                <div class="table-container">

                    <table>
                        <tr>
                            <th>Policy</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>

                        <?php
                        $query = mysqli_query($conn, "
                    SELECT compliance_records.*, policies.policy_name, policies.description
                    FROM compliance_records
                    INNER JOIN policies
                    ON compliance_records.policy_id = policies.id
                    WHERE compliance_records.user_id='$userID'
                ");

                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>

                            <tr>
                                <td><?php echo $row['policy_name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['compliance_status']; ?></td>
                                <td><?php echo $row['updated_at']; ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </div>
    </div>

</body>

</html>