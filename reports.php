<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container dashboard">

<div class="sidebar glass">
    <h2>Reports</h2>

    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li><a href="audit_logs.php">Audit Logs</a></li>
    </ul>

    <a class="logout" href="logout.php">Logout</a>
</div>

<div class="main-content glass">
    <h1>Compliance Reports</h1>

    <div class="cards">
        <div class="card glass">
            <h3>Generated Reports</h3>
            <p>12</p>
        </div>

        <div class="card glass">
            <h3>Pending Reports</h3>
            <p>2</p>
        </div>
    </div>
</div>

</div>

</body>
</html>