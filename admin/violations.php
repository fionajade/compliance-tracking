<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* CREATE VIOLATION */
if (isset($_POST['create_violation'])) {

    $user_id = $_POST['user_id'];
    $violation_type = mysqli_real_escape_string($conn, $_POST['violation_type']);
    $severity = $_POST['severity'];

    mysqli_query($conn, "
        INSERT INTO violations (user_id, violation_type, severity, status)
        VALUES ('$user_id', '$violation_type', '$severity', 'Open')
    ");

    /* OPTIONAL: increase violation count if column exists */
    mysqli_query($conn, "
        UPDATE users
        SET violation_count = violation_count + 1
        WHERE id = '$user_id'
    ");
}

/* RESOLVE VIOLATION */
if (isset($_POST['resolve_violation'])) {

    $id = $_POST['violation_id'];

    mysqli_query($conn, "
        UPDATE violations
        SET status = 'Resolved'
        WHERE id = '$id'
    ");
}

/* FETCH VIOLATIONS */
$violations = mysqli_query($conn, "
SELECT violations.*, users.username
FROM violations
LEFT JOIN users ON users.id = violations.user_id
ORDER BY violations.id DESC
");

/* USERS LIST */
$users = mysqli_query($conn, "SELECT id, username FROM users");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>⚠️ Violations (Discipline System)</h1>

            <!-- CREATE VIOLATION FORM -->
            <div class="glass" style="padding:20px; margin-top:20px;">

                <h3>Create Violation</h3>

                <form method="POST">

                    <select name="user_id" required>
                        <option value="">Select User</option>
                        <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                            <option value="<?= $u['id'] ?>">
                                <?= $u['username'] ?>
                            </option>
                        <?php } ?>
                    </select>

                    <input type="text" name="violation_type" placeholder="Violation Type" required>

                    <select name="severity" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>

                    <button type="submit" name="create_violation">
                        Create Violation
                    </button>

                </form>

            </div>

            <!-- TABLE -->
            <div class="glass" style="margin-top:25px; padding:20px; overflow-x:auto;">
                <div class="table-container">
                    <table border="1" width="100%" cellpadding="8">

                        <tr>
                            <th>Employee</th>
                            <th>Violation</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>

                        <?php while ($v = mysqli_fetch_assoc($violations)) { ?>

                            <tr>

                                <td>
                                    <?= $v['username'] ?? 'Unknown' ?>
                                    <br>
                                    <small>EMP-<?= $v['user_id'] ?></small>
                                </td>

                                <td><?= $v['violation_type'] ?></td>

                                <td>
                                    <?php
                                    if ($v['severity'] == 'High') echo "🔴 High";
                                    elseif ($v['severity'] == 'Medium') echo "🟡 Medium";
                                    else echo "🟢 Low";
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($v['status'] == 'Resolved') echo "🟢 Resolved";
                                    else echo "🔴 Open";
                                    ?>
                                </td>

                                <td><?= $v['created_at'] ?></td>

                                <td>

                                    <?php if ($v['status'] != 'Resolved') { ?>

                                        <form method="POST">
                                            <input type="hidden" name="violation_id" value="<?= $v['id'] ?>">
                                            <button type="submit" name="resolve_violation">
                                                Resolve
                                            </button>
                                        </form>

                                    <?php } else { ?>
                                        Done
                                    <?php } ?>

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