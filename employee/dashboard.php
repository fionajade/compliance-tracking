<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$userID = $_SESSION['id'];
$name = $_SESSION['username'];

/* ================= COMPLIANCE ================= */
$totalPolicies = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM compliance_records WHERE user_id='$userID'"
));

$compliant = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM compliance_records WHERE user_id='$userID' AND compliance_status='Compliant'"
));

$pending = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM compliance_records WHERE user_id='$userID' AND compliance_status='Pending'"
));

$nonCompliant = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM compliance_records WHERE user_id='$userID' AND compliance_status='Non-Compliant'"
));

$rate = ($totalPolicies > 0) ? ($compliant / $totalPolicies) * 100 : 0;

/* ================= TASKS ================= */
$totalTasks = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM tasks WHERE assigned_to='$userID'"
));

$completedTasks = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM tasks WHERE assigned_to='$userID' AND status='Completed'"
));

/* ================= INCIDENTS ================= */
$totalIncidents = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM incident_reports WHERE user_id='$userID'"
));

$pendingIncidents = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM incident_reports WHERE user_id='$userID' AND status='Pending'"
));

/* ================= VIOLATIONS ================= */
$violations = mysqli_num_rows(mysqli_query(
    $conn,
    "SELECT * FROM violations WHERE user_id='$userID'"
));

?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>




<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <?php
            $firstName = explode(' ', trim($name))[0] ?? $name;
            $currentSection = 'Dashboard';
            ?>

            <div class="section-bar">
                <div class="section-name"><?= htmlspecialchars($currentSection) ?></div>
            </div>

            <div class="dashboard-hero glass">
                <div class="hero-text">
                    <h1>Welcome, <?= htmlspecialchars($firstName) ?></h1>
                    <p>Here is your daily compliance and risk overview. You have tasks and incidents that need your attention.</p>
                </div>
                <div class="hero-summary">
                    <div class="hero-summary-title">Compliance Rate</div>
                    <div class="hero-summary-value"><?php echo round($rate); ?>%</div>
                    <div class="hero-summary-meta"><?php echo $compliant; ?> compliant · <?php echo $pending; ?> pending · <?php echo $totalIncidents; ?> incidents</div>
                </div>
            </div>

            <!-- CARDS -->
            <div class="cards">

                <div class="card glass">
                    <h3>Compliant</h3>
                    <p><?php echo $compliant; ?></p>
                </div>

                <div class="card glass">
                    <h3>Pending</h3>
                    <p><?php echo $pending; ?></p>
                </div>

                <div class="card glass">
                    <h3>Violations</h3>
                    <p><?php echo $violations; ?></p>
                </div>

                <div class="card glass">
                    <h3>Tasks</h3>
                    <p><?php echo $totalTasks; ?></p>
                </div>

                <div class="card glass">
                    <h3>Incidents</h3>
                    <p><?php echo $totalIncidents; ?></p>
                </div>

            </div>

            <!-- COMPLIANCE -->
            <div class="glass" style="padding:20px;margin-top:20px;">
                <h2>Compliance Status</h2>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Policy</th>
                            <th>Status</th>
                            <th>Updated</th>
                        </tr>

                        <?php
                        $q = mysqli_query(
                            $conn,
                            "SELECT compliance_records.*, policies.policy_name
FROM compliance_records
INNER JOIN policies ON compliance_records.policy_id = policies.id
WHERE compliance_records.user_id='$userID'"
                        );

                        while ($r = mysqli_fetch_assoc($q)) {
                        ?>
                            <tr>
                                <td><?php echo $r['policy_name']; ?></td>
                                <td><?php echo $r['compliance_status']; ?></td>
                                <td><?php echo $r['updated_at']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- TASKS -->
            <div class="glass" style="padding:20px;margin-top:20px;">
                <h2>My Tasks</h2>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Deadline</th>
                        </tr>

                        <?php
                        $t = mysqli_query($conn, "SELECT * FROM tasks WHERE assigned_to='$userID'");
                        while ($row = mysqli_fetch_assoc($t)) {
                        ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['priority']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- INCIDENTS -->
            <div class="glass" style="padding:20px;margin-top:20px;">
                <h2>My Incident Reports</h2>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Title</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        $i = mysqli_query($conn, "SELECT * FROM incident_reports WHERE user_id='$userID' ORDER BY id DESC");
                        while ($r = mysqli_fetch_assoc($i)) {
                        ?>
                            <tr>
                                <td><?php echo $r['title']; ?></td>
                                <td><?php echo $r['severity']; ?></td>
                                <td><?php echo $r['status']; ?></td>
                                <td><?php echo $r['date_reported']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

            <!-- LOGS -->
            <div class="glass" style="padding:20px;margin-top:20px;">
                <h2>Activity Logs</h2>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>

                        <?php
                        $l = mysqli_query($conn, "SELECT * FROM activity_logs WHERE user_id='$userID' ORDER BY log_time DESC");
                        while ($log = mysqli_fetch_assoc($l)) {
                        ?>
                            <tr>
                                <td><?php echo $log['action']; ?></td>
                                <td><?php echo $log['log_time']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>

</html>