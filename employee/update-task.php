<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

$task_id = $_POST['task_id'];
$status = $_POST['status'] ?? null;

/* =========================
   GET TASK INFO
========================= */
$taskQuery = mysqli_query($conn, "SELECT * FROM tasks WHERE id='$task_id'");
$task = mysqli_fetch_assoc($taskQuery);

if (!$task) {
    header("Location: tasks.php");
    exit();
}

/* =========================
   PERMISSION CHECK
========================= */
$canEdit = false;

// RULE 1: assigned user
if ($task['assigned_to'] == $user_id) {
    $canEdit = true;
}

// RULE 2: same department
if ($task['department'] == ($_SESSION['department'] ?? '')) {
    $canEdit = true;
}

/* =========================
   VIOLATION HANDLING
========================= */
if (!$canEdit) {

    mysqli_query($conn, "
        INSERT INTO activity_logs (user_id, action, task_id, log_time)
        VALUES (
            '$user_id',
            'VIOLATION: unauthorized task update attempt (Task ID: $task_id)',
            '$task_id',
            NOW()
        )
    ");

    header("Location: tasks.php?error=violation");
    exit();
}

/* =========================
   STATUS UPDATE
========================= */
if ($status) {

    mysqli_query($conn, "
        UPDATE tasks
        SET status='$status'
        WHERE id='$task_id'
    ");

    mysqli_query($conn, "
        INSERT INTO activity_logs (user_id, action, task_id, log_time)
        VALUES (
            '$user_id',
            'Updated task status to $status',
            '$task_id',
            NOW()
        )
    ");
}

header("Location: tasks.php?success=1");
exit();
?>