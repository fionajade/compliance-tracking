<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

/* =========================
   CHECK IF USER IS LOCKED
========================= */
$lockCheck = mysqli_query($conn, "SELECT is_locked, violation_count FROM users WHERE id='$user_id'");
$userData = mysqli_fetch_assoc($lockCheck);

if ($userData['is_locked'] == 1) {
    echo "<h2 style='color:red;text-align:center;margin-top:50px;'>
            🔒 Your account has been locked due to multiple violations.
          </h2>";
    exit();
}

/* =========================
   GET TASKS
========================= */
$result = mysqli_query($conn, "SELECT * FROM tasks ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tasks Kanban</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/task-style.css">
</head>

<body>

<div class="container dashboard">

    <?php include(__DIR__ . '/../includes/employee-sidebar.php'); ?>

    <div class="main-content glass">

        <h1>📌 Task Board</h1>

        <a href="add-task.php" class="btn">+ Add Task</a>

        <div class="kanban">

        <!-- ================= NOT STARTED ================= -->
        <div class="kanban-column glass">
            <h2>🟡 Not Started</h2>

            <?php
            mysqli_data_seek($result, 0);
            $depts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['status'] == "Not Started") {
                    $depts[$row['department']][] = $row;
                }
            }

            foreach ($depts as $deptName => $tasks) {
            ?>

            <h3 style="margin-top:15px; font-size:14px; opacity:0.8;">
                📁 <?= $deptName ?>
            </h3>

            <?php foreach ($tasks as $row) { ?>

            <div class="task-card">

                <h3><?= $row['title'] ?></h3>
                <p>Priority: <?= $row['priority'] ?></p>
                <p>Dept: <?= $row['department'] ?></p>
                <p>Due: <?= $row['deadline'] ?></p>

                <div style="display:flex; gap:5px; flex-wrap:wrap;">

                    <form method="POST" action="update-task.php">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="In Progress">
                        <button class="btn small">Start</button>
                    </form>

                    <a class="btn small" href="add-task.php?id=<?= $row['id'] ?>">
                        Edit
                    </a>

                </div>

            </div>

            <?php } ?>

            <?php } ?>

        </div>

        <!-- ================= IN PROGRESS ================= -->
        <div class="kanban-column glass">
            <h2>🔵 In Progress</h2>

            <?php
            mysqli_data_seek($result, 0);
            $depts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['status'] == "In Progress") {
                    $depts[$row['department']][] = $row;
                }
            }

            foreach ($depts as $deptName => $tasks) {
            ?>

            <h3 style="margin-top:15px; font-size:14px; opacity:0.8;">
                📁 <?= $deptName ?>
            </h3>

            <?php foreach ($tasks as $row) { ?>

            <div class="task-card">

                <h3><?= $row['title'] ?></h3>
                <p>Priority: <?= $row['priority'] ?></p>
                <p>Dept: <?= $row['department'] ?></p>
                <p>Due: <?= $row['deadline'] ?></p>

                <div style="display:flex; gap:5px; flex-wrap:wrap;">

                    <form method="POST" action="update-task.php">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button class="btn small" name="status" value="Completed">
                            Done
                        </button>
                    </form>

                    <a class="btn small" href="add-task.php?id=<?= $row['id'] ?>">
                        Edit
                    </a>

                </div>

            </div>

            <?php } ?>

            <?php } ?>

        </div>

        <!-- ================= COMPLETED ================= -->
        <div class="kanban-column glass">
            <h2>🟢 Completed</h2>

            <?php
            mysqli_data_seek($result, 0);
            $depts = [];

            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['status'] == "Completed") {
                    $depts[$row['department']][] = $row;
                }
            }

            foreach ($depts as $deptName => $tasks) {
            ?>

            <h3 style="margin-top:15px; font-size:14px; opacity:0.8;">
                📁 <?= $deptName ?>
            </h3>

            <?php foreach ($tasks as $row) { ?>

            <div class="task-card done">

                <h3><?= $row['title'] ?></h3>
                <p>Priority: <?= $row['priority'] ?></p>
                <p>Dept: <?= $row['department'] ?></p>
                <p>Due: <?= $row['deadline'] ?></p>

                <a class="btn small" href="add-task.php?id=<?= $row['id'] ?>">
                    Edit
                </a>

            </div>

            <?php } ?>

            <?php } ?>

        </div>

        </div>

    </div>
</div>

</body>
</html>