<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../index.php");
    exit();
}

$userID = $_SESSION['id'];
$name = $_SESSION['username'];
?>

<?php include("__DIR__ . '/../includes/header.php"); ?>

<body>

<div class="container dashboard">

<?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

<div class="main-content glass">

    <h1>My Incident Reports</h1>

    <div class="glass" style="padding:20px;margin-top:20px;">

        <div class="table-container">

            <table>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Date Reported</th>
                </tr>

                <?php
                $query = mysqli_query($conn,"
                    SELECT * FROM incident_reports
                    WHERE user_id='$userID'
                    ORDER BY date_reported DESC
                ");

                while($row = mysqli_fetch_assoc($query)){
                ?>

                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['severity']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td><?php echo $row['date_reported']; ?></td>
                </tr>

                <?php } ?>

            </table>

        </div>

    </div>

</div>
</div>

</body>
</html>