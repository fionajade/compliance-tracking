<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    exit("Access Denied");
}

$user_id = $_SESSION['id'];

/* UPDATE TASK STATUS */
if (isset($_POST['update_task'])) {

    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    mysqli_query($conn, "
        UPDATE tasks
        SET status='$status'
        WHERE id='$task_id' AND assigned_to='$user_id'
    ");

    mysqli_query($conn, "
        INSERT INTO activity_logs (user_id, action, task_id)
        VALUES ('$user_id', 'Updated task status to $status', '$task_id')
    ");
}

/* GET ASSIGNED TASKS */
$tasks = mysqli_query($conn, "
    SELECT *
    FROM tasks
    WHERE assigned_to='$user_id'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

    <?php include('sidebar.php'); ?>

    <div class="main-content glass">

        <h1>📋 My Tasks</h1>
        <p style="opacity:0.7;">Receive assigned tasks and update progress</p>

        <!-- TASK LIST -->
        <div class="glass" style="padding:20px;margin-top:20px;">

            <div class="table-container">

                <table border="1" width="100%" cellpadding="8">

                    <tr>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php while ($t = mysqli_fetch_assoc($tasks)) { ?>

                        <tr>

                            <td><?= $t['title'] ?></td>

                            <td>
                                <?php
                                if ($t['priority'] == 'High') echo "🔴 High";
                                elseif ($t['priority'] == 'Medium') echo "🟡 Medium";
                                else echo "🟢 Low";
                                ?>
                            </td>

                            <td><?= $t['deadline'] ?></td>

                            <td><?= $t['status'] ?></td>

                            <td>

                                <form method="POST">

                                    <input type="hidden" name="task_id" value="<?= $t['id'] ?>">

                                    <select name="status">
                                        <option>Not Started</option>
                                        <option>In Progress</option>
                                        <option>Completed</option>
                                    </select>

                                    <button type="submit" name="update_task">
                                        Update
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php } ?>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>