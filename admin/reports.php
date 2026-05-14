<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* =========================
   TREND ANALYSIS
========================= */

/* Violations per user */
$violationsTrend = mysqli_query($conn, "
SELECT DATE(created_at) as date, COUNT(*) as total
FROM violations
GROUP BY DATE(created_at)
ORDER BY date ASC
");

/* Total counts */
$totalUsers = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$totalViolations = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM violations"));
$totalIncidents = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM incident_reports"));

/* HIGH RISK USERS */
$highRiskUsers = mysqli_query($conn, "
SELECT users.username, users.id,
COUNT(violations.id) as violation_count
FROM users
LEFT JOIN violations ON violations.user_id = users.id
GROUP BY users.id
HAVING violation_count >= 3
ORDER BY violation_count DESC
");

/* DEPARTMENT PERFORMANCE */
$deptPerformance = mysqli_query($conn, "
SELECT users.department,
COUNT(violations.id) as violations,
COUNT(incident_reports.id) as incidents
FROM users
LEFT JOIN violations ON violations.user_id = users.id
LEFT JOIN incident_reports ON incident_reports.user_id = users.id
GROUP BY users.department
ORDER BY violations DESC
");

/* RISK DISTRIBUTION */
$lowRisk = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total FROM violations WHERE severity='Low'
"))['total'];

$medRisk = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total FROM violations WHERE severity='Medium'
"))['total'];

$highRisk = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT COUNT(*) as total FROM violations WHERE severity='High'
"))['total'];
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>📈 Reports & Analytics (Decision Support System)</h1>

            <p style="opacity:0.7;">
                Executive intelligence dashboard for compliance decision-making
            </p>

            <!-- KPI CARDS -->
            <div class="cards" style="margin-top:20px;">

                <div class="card glass">
                    <h3>👥 Users</h3>
                    <p><?= $totalUsers ?></p>
                </div>

                <div class="card glass">
                    <h3>⚠ Violations</h3>
                    <p><?= $totalViolations ?></p>
                </div>

                <div class="card glass">
                    <h3>🚨 Incidents</h3>
                    <p><?= $totalIncidents ?></p>
                </div>

            </div>

            <!-- RISK DISTRIBUTION -->
            <div class="glass" style="margin-top:25px; padding:20px;">

                <h3>📊 Risk Distribution</h3>

                <p>🟢 Low Risk: <?= $lowRisk ?></p>
                <p>🟡 Medium Risk: <?= $medRisk ?></p>
                <p>🔴 High Risk: <?= $highRisk ?></p>

            </div>

            <!-- HIGH RISK USERS -->
            <div class="glass" style="margin-top:20px; padding:20px;">

                <h3>🚨 High Risk Employees</h3>
                <div class="table-container">
                    <table width="100%" border="1" cellpadding="8">

                        <tr>
                            <th>User</th>
                            <th>Violations</th>
                            <th>Risk Level</th>
                        </tr>

                        <?php while ($u = mysqli_fetch_assoc($highRiskUsers)) { ?>

                            <tr>

                                <td><?= $u['username'] ?></td>

                                <td><?= $u['violation_count'] ?></td>

                                <td style="color:red;">HIGH RISK</td>

                            </tr>

                        <?php } ?>
                    </table>
                </div>

            </div>

            <!-- DEPARTMENT PERFORMANCE -->
            <div class="glass" style="margin-top:20px; padding:20px;">

                <h3>🏢 Department Performance</h3>
                <div class="table-container">
                    <table width="100%" border="1" cellpadding="8">

                        <tr>
                            <th>Department</th>
                            <th>Violations</th>
                            <th>Incidents</th>
                            <th>Performance</th>
                        </tr>

                        <?php while ($d = mysqli_fetch_assoc($deptPerformance)) { ?>

                            <tr>

                                <td><?= $d['department'] ?? 'N/A' ?></td>

                                <td><?= $d['violations'] ?></td>

                                <td><?= $d['incidents'] ?></td>

                                <td>
                                    <?php
                                    $score = $d['violations'] + $d['incidents'];

                                    if ($score >= 10) echo "🔴 Poor";
                                    elseif ($score >= 5) echo "🟡 Average";
                                    else echo "🟢 Good";
                                    ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>
            </div>
            <!-- TREND ANALYSIS -->
            <div class="glass" style="padding:20px;margin-top:20px;">

                <h3>📈 Violation Trend (By Date)</h3>
                <div class="table-container">
                    <table width="100%" border="1" cellpadding="8">

                        <tr>
                            <th>Date</th>
                            <th>Total Violations</th>
                        </tr>

                        <?php while ($t = mysqli_fetch_assoc($violationsTrend)) { ?>

                            <tr>
                                <td><?= $t['date'] ?></td>
                                <td><?= $t['total'] ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </div>

</body>

</html>1