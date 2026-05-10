<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['id'];

/* COMPLIANCE COUNTS */
$totalQuery = mysqli_query(
    $conn,
    "SELECT * FROM compliance_records WHERE user_id='$userID'"
);

$totalPolicies = mysqli_num_rows($totalQuery);

$compliantQuery = mysqli_query(
    $conn,
    "SELECT * FROM compliance_records
WHERE user_id='$userID'
AND compliance_status='Compliant'"
);

$compliantCount = mysqli_num_rows($compliantQuery);

$pendingQuery = mysqli_query(
    $conn,
    "SELECT * FROM compliance_records
WHERE user_id='$userID'
AND compliance_status='Pending'"
);

$pendingCount = mysqli_num_rows($pendingQuery);

$nonCompliantQuery = mysqli_query(
    $conn,
    "SELECT * FROM compliance_records
WHERE user_id='$userID'
AND compliance_status='Non-Compliant'"
);

$nonCompliantCount = mysqli_num_rows($nonCompliantQuery);

$complianceRate = 0;

if ($totalPolicies > 0) {
    $complianceRate = ($compliantCount / $totalPolicies) * 100;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

        <div class="main-content glass">

            <h1>Welcome, <?php echo $_SESSION['fullname']; ?></h1>

            <!-- OVERVIEW CARDS -->
            <div class="cards">

                <div class="card glass">
                    <h3>Compliance Rate</h3>
                    <p><?php echo round($complianceRate); ?>%</p>
                </div>

                <div class="card glass">
                    <h3>Completed Policies</h3>
                    <p><?php echo $compliantCount; ?></p>
                </div>

                <div class="card glass">
                    <h3>Pending Tasks</h3>
                    <p><?php echo $pendingCount; ?></p>
                </div>

                <div class="card glass">
                    <h3>Violations</h3>
                    <p><?php echo $nonCompliantCount; ?></p>
                </div>

            </div>

            <!-- COMPLIANCE STATUS -->
            <div class="glass" style="padding:30px; margin-top:30px;">

                <h2>Compliance Status</h2>
                <br>

                <div class="table-container">

                    <table>

                        <tr>
                            <th>Policy</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>

                        <?php

                        $policyQuery = mysqli_query(
                            $conn,
                            "SELECT compliance_records.*,
                    policies.policy_name,
                    policies.description

                    FROM compliance_records

                    INNER JOIN policies
                    ON compliance_records.policy_id = policies.id

                    WHERE compliance_records.user_id='$userID'"
                        );

                        while ($row = mysqli_fetch_assoc($policyQuery)) {
                        ?>

                            <tr>

                                <td>
                                    <?php echo $row['policy_name']; ?>
                                </td>

                                <td>
                                    <?php echo $row['description']; ?>
                                </td>

                                <td>

                                    <?php

                                    if ($row['compliance_status'] == "Compliant") {
                                        echo "<span style='color:lightgreen;'>Compliant</span>";
                                    } elseif ($row['compliance_status'] == "Pending") {
                                        echo "<span style='color:yellow;'>Pending</span>";
                                    } else {
                                        echo "<span style='color:#ff7b7b;'>Non-Compliant</span>";
                                    }

                                    ?>

                                </td>

                                <td>
                                    <?php echo $row['updated_at']; ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

            <!-- TASKS SECTION -->
            <div class="glass" style="padding:30px; margin-top:30px;">

                <h2>Assigned Tasks</h2>
                <br>

                <div class="table-container">

                    <table>

                        <tr>
                            <th>Task</th>
                            <th>Priority</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>

                        <tr>
                            <td>Review Data Privacy Policy</td>
                            <td>High</td>
                            <td>May 15, 2026</td>
                            <td>
                                <span style="color:yellow;">
                                    Pending
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>Update Password</td>
                            <td>Medium</td>
                            <td>May 20, 2026</td>
                            <td>
                                <span style="color:lightgreen;">
                                    Completed
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td>Complete Security Awareness Training</td>
                            <td>High</td>
                            <td>May 25, 2026</td>
                            <td>
                                <span style="color:#ff7b7b;">
                                    Not Started
                                </span>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

            <!-- RECENT ACTIVITY -->
            <div class="glass" style="padding:30px; margin-top:30px;">

                <h2>Recent Activity Logs</h2>
                <br>

                <div class="table-container">

                    <table>

                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                        </tr>

                        <?php

                        $logsQuery = mysqli_query(
                            $conn,
                            "SELECT * FROM activity_logs
                    WHERE user_id='$userID'
                    ORDER BY log_time DESC"
                        );

                        while ($log = mysqli_fetch_assoc($logsQuery)) {
                        ?>

                            <tr>

                                <td>
                                    <?php echo $log['action']; ?>
                                </td>

                                <td>
                                    <?php echo $log['log_time']; ?>
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