<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* FILTERS */
$where = "WHERE 1=1";

if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where .= " AND (users.username LIKE '%$search%'
                OR activity_logs.action LIKE '%$search%'
                OR activity_logs.module LIKE '%$search%')";
}

if (!empty($_GET['severity'])) {
    $severity = mysqli_real_escape_string($conn, $_GET['severity']);
    $where .= " AND activity_logs.severity = '$severity'";
}

/* DATA */
$data = mysqli_query($conn, "
SELECT activity_logs.*, users.username
FROM activity_logs
INNER JOIN users ON users.id = activity_logs.user_id
$where
ORDER BY log_time DESC
");
?>

<!DOCTYPE html>
<html>
<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <h1>🧾 Audit Logs (Truth Tracking System)</h1>

        <!-- FILTER PANEL -->
        <form method="GET" style="margin-top:15px; display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search user / action / module">

            <select name="severity">
                <option value="">All Severity</option>
                <option value="info">Info</option>
                <option value="warning">Warning</option>
                <option value="critical">Critical</option>
            </select>

            <button type="submit">Filter</button>
        </form>

        <!-- TABLE -->
        <div class="glass" style="padding:20px;margin-top:20px;">
            <div class="table-container">

                <table border="1" width="100%" cellpadding="8">
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Status</th>
                        <th>Severity</th>
                        <th>Task ID</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($l = mysqli_fetch_assoc($data)) { ?>

                        <tr>

                            <td><?= $l['username'] ?></td>

                            <td><?= $l['action'] ?></td>

                            <td><?= $l['module'] ?></td>

                            <td><?= $l['status'] ?></td>

                            <!-- SEVERITY HIGHLIGHT -->
                            <td>
                                <?php if ($l['severity'] == 'critical') { ?>
                                    <span style="color:red;font-weight:bold;">⚠ <?= $l['severity'] ?></span>
                                <?php } elseif ($l['severity'] == 'warning') { ?>
                                    <span style="color:orange;"><?= $l['severity'] ?></span>
                                <?php } else { ?>
                                    <span style="color:green;"><?= $l['severity'] ?></span>
                                <?php } ?>
                            </td>

                            <td><?= $l['task_id'] ?></td>

                            <td><?= $l['ip_address'] ?></td>

                            <td><?= $l['log_time'] ?></td>

                        </tr>

                    <?php } ?>

                </table>

            </div>
        </div>

    </div>
</div>

</body>
</html>