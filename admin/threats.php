<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* 1. HIGH SEVERITY INCIDENTS */
$highIncidents = mysqli_query($conn, "
SELECT incident_reports.*, users.username
FROM incident_reports
LEFT JOIN users ON users.id = incident_reports.user_id
WHERE incident_reports.severity = 'High'
ORDER BY date_reported DESC
");

/* 2. REPEATED VIOLATIONS (3 OR MORE) */
$repeatViolators = mysqli_query($conn, "
SELECT users.id, users.username, COUNT(violations.id) as violation_count
FROM users
LEFT JOIN violations ON violations.user_id = users.id
GROUP BY users.id
HAVING violation_count >= 3
");

/* 3. LOCKED ACCOUNTS (from logs) */
$lockedAccounts = mysqli_query($conn, "
SELECT DISTINCT users.id, users.username
FROM users
INNER JOIN activity_logs ON activity_logs.user_id = users.id
WHERE activity_logs.action LIKE '%LOCKED%'
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>📡 Threat Alerts (System Intelligence)</h1>

            <p style="opacity:0.7;">
                Auto-generated security risks based on system behavior
            </p>

            <!-- SUMMARY -->
            <div class="cards" style="margin-top:20px;">

                <div class="card glass">
                    <h3>🔥 High Severity Incidents</h3>
                    <p><?= mysqli_num_rows($highIncidents) ?></p>
                </div>

                <div class="card glass">
                    <h3>⚠️ Repeat Violators</h3>
                    <p><?= mysqli_num_rows($repeatViolators) ?></p>
                </div>

                <div class="card glass">
                    <h3>🔒 Locked Accounts</h3>
                    <p><?= mysqli_num_rows($lockedAccounts) ?></p>
                </div>

            </div>

            <!-- HIGH INCIDENTS -->
            <div class="glass" style="margin-top:25px; padding:20px;">

                <h3>🔥 High Severity Incidents</h3>
                <div class="table-container">
                    <table width="100%" border="1" cellpadding="8">

                        <tr>
                            <th>User</th>
                            <th>Title</th>
                            <th>Severity</th>
                            <th>Date</th>
                        </tr>

                        <?php while ($i = mysqli_fetch_assoc($highIncidents)) { ?>

                            <tr>
                                <td><?= $i['username'] ?></td>
                                <td><?= $i['title'] ?></td>
                                <td style="color:red;">HIGH</td>
                                <td><?= $i['date_reported'] ?></td>
                            </tr>

                        <?php } ?>

                    </table>

                </div>
            </div>
                <!-- REPEAT VIOLATORS -->
                <div class="glass" style="margin-top:25px; padding:20px;">

                    <h3>⚠️ Repeat Violators (3+ violations)</h3>
                    <div class="table-container">
                        <table width="100%" border="1" cellpadding="8">

                            <tr>
                                <th>User</th>
                                <th>Total Violations</th>
                                <th>Risk Level</th>
                            </tr>

                            <?php while ($v = mysqli_fetch_assoc($repeatViolators)) { ?>

                                <tr>
                                    <td><?= $v['username'] ?></td>
                                    <td><?= $v['violation_count'] ?></td>
                                    <td style="color:orange;">HIGH RISK</td>
                                </tr>

                            <?php } ?>

                        </table>

                    </div>
                </div>
                    <!-- LOCKED ACCOUNTS -->
                    <div class="glass" style="margin-top:25px; padding:20px;">

                        <h3>🔒 Locked Accounts</h3>
                        <div class="table-container">
                            <table width="100%" border="1" cellpadding="8">

                                <tr>
                                    <th>User</th>
                                    <th>Status</th>
                                </tr>

                                <?php while ($l = mysqli_fetch_assoc($lockedAccounts)) { ?>

                                    <tr>
                                        <td><?= $l['username'] ?></td>
                                        <td style="color:red;">LOCKED / SECURITY FLAGGED</td>
                                    </tr>

                                <?php } ?>

                            </table>

                        </div>

                    </div>
                </div>

</body>

</html>