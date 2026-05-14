<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* COUNTS */
$users = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$policies = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM policies"));
$incidents = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM incident_reports"));
$violations = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM violations"));
$tasks = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tasks"));

/* COMPLIANCE STATUS */
$compliant = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM compliance_records WHERE compliance_status='Compliant'"));
$pending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM compliance_records WHERE compliance_status='Pending'"));
$non = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM compliance_records WHERE compliance_status='Non-Compliant'"));

$total = $compliant + $pending + $non;
$rate = ($total > 0) ? round(($compliant / $total) * 100) : 0;
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <!-- HEADER -->
        <div style="margin-bottom: 20px;">
            <h1 style="margin-bottom: 5px;">Security & Compliance Dashboard</h1>
            <p style="opacity: 0.7; margin: 0;">
                Overview of system activity, compliance status, and operational metrics
            </p>
        </div>

        <!-- CARDS -->
        <div class="cards" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:15px;">

            <div class="card glass">
                <h3>Users</h3>
                <p><?= $users ?></p>
            </div>

            <div class="card glass">
                <h3>Policies</h3>
                <p><?= $policies ?></p>
            </div>

            <div class="card glass">
                <h3>Incidents</h3>
                <p><?= $incidents ?></p>
            </div>

            <div class="card glass">
                <h3>Violations</h3>
                <p><?= $violations ?></p>
            </div>

            <div class="card glass">
                <h3>Tasks</h3>
                <p><?= $tasks ?></p>
            </div>

            <div class="card glass">
                <h3>Compliance Rate</h3>
                <p><?= $rate ?>%</p>
            </div>

        </div>

        <!-- CHART SECTION (FIXED SIZE) -->
        <div class="glass" style="margin-top: 30px; padding: 25px; border-radius: 15px;">

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h2 style="margin:0;">Compliance Overview</h2>
                <span style="opacity:0.7; font-size:14px;">Real-time distribution</span>
            </div>

            <!-- SMALLER CHART WRAPPER -->
            <div style="width: 100%; max-width: 380px; height: 260px; margin: 0 auto;">
                <canvas id="complianceChart"></canvas>
            </div>

        </div>

    </div>

</div>

<script>
const ctx = document.getElementById('complianceChart');

new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Compliant', 'Pending', 'Non-Compliant'],
        datasets: [{
            data: [<?= $compliant ?>, <?= $pending ?>, <?= $non ?>],
            backgroundColor: ['#00ff99', '#ffcc00', '#ff4d4d'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

</body>
</html>