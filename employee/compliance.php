<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Compliance Status</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

        <div class="main-content glass">

            <h1>Compliance Status</h1>

            <div class="table-container">

                <table>

                    <tr>
                        <th>Policy</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                    </tr>

                    <?php
                    $query = mysqli_query(
                        $conn,
                        "SELECT compliance_records.*,
policies.policy_name,
policies.description

FROM compliance_records

INNER JOIN policies
ON compliance_records.policy_id = policies.id

WHERE compliance_records.user_id='$userID'"
                    );

                    while ($row = mysqli_fetch_assoc($query)) {
                    ?>

                        <tr>

                            <td><?php echo $row['policy_name']; ?></td>

                            <td><?php echo $row['description']; ?></td>

                            <td>

                                <?php
                                if ($row['compliance_status'] == 'Compliant') {
                                    echo "<span style='color:lightgreen;'>Compliant</span>";
                                } elseif ($row['compliance_status'] == 'Pending') {
                                    echo "<span style='color:yellow;'>Pending</span>";
                                } else {
                                    echo "<span style='color:#ff7b7b;'>Non-Compliant</span>";
                                }
                                ?>

                            </td>

                            <td><?php echo $row['updated_at']; ?></td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>
    </div>

</body>

</html>