<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* MAIN DATA */
$data = mysqli_query($conn, "
SELECT compliance_records.*, users.username, users.department, policies.policy_name
FROM compliance_records
INNER JOIN users ON compliance_records.user_id = users.id
INNER JOIN policies ON compliance_records.policy_id = policies.id
");

/* RISK COUNTS */
$total = mysqli_num_rows($data);

/* RESET POINTER */
mysqli_data_seek($data, 0);

/* RISK ANALYTICS */
$nonCompliant = mysqli_query($conn, "
SELECT COUNT(*) as total FROM compliance_records
WHERE compliance_status = 'Non-Compliant'
");

$pending = mysqli_query($conn, "
SELECT COUNT(*) as total FROM compliance_records
WHERE compliance_status = 'Pending'
");

$compliant = mysqli_query($conn, "
SELECT COUNT(*) as total FROM compliance_records
WHERE compliance_status = 'Compliant'
");

/* DEPARTMENT RISK */
$deptRisk = mysqli_query($conn, "
SELECT users.department,
SUM(CASE WHEN compliance_status='Non-Compliant' THEN 3
         WHEN compliance_status='Pending' THEN 1
         ELSE 0 END) as risk_score
FROM compliance_records
INNER JOIN users ON users.id = compliance_records.user_id
GROUP BY users.department
ORDER BY risk_score DESC
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>📊 Compliance Risk Monitoring System</h1>

            <p style="opacity:0.7;">
                Real-time compliance intelligence and risk detection
            </p>

            <!-- SUMMARY CARDS -->
            <div class="cards" style="margin-top:20px;">

                <div class="card glass">
                    <h3>✔ Compliant</h3>
                    <p><?= mysqli_fetch_assoc($compliant)['total'] ?></p>
                </div>

                <div class="card glass">
                    <h3>⏳ Pending</h3>
                    <p><?= mysqli_fetch_assoc($pending)['total'] ?></p>
                </div>

                <div class="card glass">
                    <h3>⚠ Non-Compliant</h3>
                    <p><?= mysqli_fetch_assoc($nonCompliant)['total'] ?></p>
                </div>

            </div>

            <!-- DEPARTMENT RISK -->
            <div class="glass" style="padding:20px;margin-top:20px;">

                <h3>🏢 Department Risk Comparison</h3>

                <div class="table-container">
                    <table border="1" width="100%" cellpadding="8">

                        <tr>
                            <th>Department</th>
                            <th>Risk Score</th>
                            <th>Risk Level</th>
                        </tr>

                        <?php while ($d = mysqli_fetch_assoc($deptRisk)) { ?>

                            <tr>

                                <td><?= $d['department'] ?? 'N/A' ?></td>

                                <td><?= $d['risk_score'] ?></td>

                                <td>
                                    <?php
                                    if ($d['risk_score'] >= 10) {
                                        echo "🔴 HIGH RISK";
                                    } elseif ($d['risk_score'] >= 5) {
                                        echo "🟡 MEDIUM RISK";
                                    } else {
                                        echo "🟢 LOW RISK";
                                    }
                                    ?>
                                </td>

                            </tr>

                        <?php } ?>
                    </table>
                </div>

            </div>

            <!-- MAIN TABLE -->
            <div class="glass" style="padding:20px;margin-top:20px;">

                <h3>Employee Compliance Records</h3>


                <div class="table-container">

                    <table border="1" width="100%" cellpadding="8">

                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Policy</th>
                            <th>Status</th>
                            <th>Risk</th>
                            <th>Updated</th>
                        </tr>

                        <?php while ($r = mysqli_fetch_assoc($data)) { ?>

                            <tr>

                                <td><?= $r['username'] ?></td>

                                <td><?= $r['department'] ?? 'N/A' ?></td>

                                <td><?= $r['policy_name'] ?></td>

                                <td>
                                    <?= $r['compliance_status'] ?>
                                </td>

                                <td>
                                    <?php
                                    if ($r['compliance_status'] == "Non-Compliant") {
                                        echo "🔴 HIGH";
                                    } elseif ($r['compliance_status'] == "Pending") {
                                        echo "🟡 MEDIUM";
                                    } else {
                                        echo "🟢 LOW";
                                    }
                                    ?>
                                </td>

                                <td><?= $r['updated_at'] ?></td>

                            </tr>

                        <?php } ?>

                    </table>

                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Policy</th>
                        <th>Status</th>
                        <th>Risk</th>
                        <th>Updated</th>
                    </tr>

                    <?php while ($r = mysqli_fetch_assoc($data)) { ?>

                        <tr>

                            <td><?= $r['username'] ?></td>

                            <td><?= $r['department'] ?? 'N/A' ?></td>

                            <td><?= $r['policy_name'] ?></td>

                            <td>
                                <?= $r['compliance_status'] ?>
                            </td>

                            <td>
                                <?php
                                if ($r['compliance_status'] == "Non-Compliant") {
                                    echo "🔴 HIGH";
                                } elseif ($r['compliance_status'] == "Pending") {
                                    echo "🟡 MEDIUM";
                                } else {
                                    echo "🟢 LOW";
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