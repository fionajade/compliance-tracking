<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'security') {
    header("Location: ../index.php");
    exit();
}

$name = $_SESSION['fullname'];

/* INCIDENTS */
$totalIncidents = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM incident_reports"));
$pendingIncidents = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM incident_reports WHERE status='Pending'"));

/* VIOLATIONS */
$totalViolations = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM violations"));
$openViolations = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM violations WHERE status='Open'"));

/* LOGS */
$totalLogs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM activity_logs"));
?>

<!DOCTYPE html>
<html>

<head>
    <title>Security Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">
        <?php include(__DIR__ . '/../includes/security-sidebar.php'); ?>
        <div class="main-content glass">
            <h1>Welcome, <?php echo $_SESSION['fullname']; ?></h1>

            <!-- CARDS -->
            <div class="cards">

                <div class="card glass">
                    <h3>Total Incidents</h3>
                    <p><?php echo $totalIncidents; ?></p>
                </div>

                <div class="card glass">
                    <h3>Pending Incidents</h3>
                    <p><?php echo $pendingIncidents; ?></p>
                </div>

                <div class="card glass">
                    <h3>Violations</h3>
                    <p><?php echo $totalViolations; ?></p>
                </div>

                <div class="card glass">
                    <h3>Open Violations</h3>
                    <p><?php echo $openViolations; ?></p>
                </div>

                <div class="card glass">
                    <h3>Audit Logs</h3>
                    <p><?php echo $totalLogs; ?></p>
                </div>

            </div>


        </div>

    </div>

</body>

</html>