<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$message = "";

if (isset($_POST['submitReport'])) {

    $report = $_POST['report'];

    $message = "Incident report submitted successfully.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Submit Report</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <div class="container dashboard">

        <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

        <div class="main-content glass">

            <h1>Submit Incident Report</h1>

            <?php
            if ($message != "") {
                echo "<p style='margin-bottom:20px; color:lightgreen;'>$message</p>";
            }
            ?>

            <form method="POST">

                <div class="input-group">
                    <label>Incident Report</label>
                    <textarea name="report" rows="8" style="width:100%; padding:15px; border-radius:12px; border:none;"></textarea>
                </div>

                <button type="submit" name="submitReport" class="btn">
                    Submit Report
                </button>

            </form>

        </div>
    </div>

</body>

</html>