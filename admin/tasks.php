<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$tasks = mysqli_query($conn, "
    SELECT tasks.*, users.fullname
    FROM tasks
    INNER JOIN users
    ON tasks.assigned_to = users.id
");
?>

<?php include("__DIR__ . '/../includes/header.php"); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>


        <div class="main-content glass">

            <h1>Task Management</h1>

            <div class="glass" style="padding:20px; margin-top:20px;">

                <div class="table-container">

                    <table>

                        <tr>
                            <th>Task</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Deadline</th>
                        </tr>

                        <?php while ($t = mysqli_fetch_assoc($tasks)) { ?>

                            <tr>

                                <td><?php echo $t['title']; ?></td>

                                <td><?php echo $t['fullname']; ?></td>

                                <td><?php echo $t['priority']; ?></td>

                                <td><?php echo $t['status']; ?></td>

                                <td><?php echo $t['deadline']; ?></td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>

        </div>
    </div>

</body>

</html>