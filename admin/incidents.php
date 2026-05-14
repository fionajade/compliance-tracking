<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    exit("Access Denied");
}

/* HANDLE UPDATE STATUS */
if (isset($_POST['update_incident'])) {

    $id = $_POST['incident_id'];
    $status = $_POST['status'];
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    mysqli_query($conn, "
        UPDATE incident_reports
        SET status = '$status',
            description = CONCAT(description, '\n\n[ADMIN NOTE] ', '$notes')
        WHERE id = $id
    ");
}

/* HANDLE ESCALATION */
if (isset($_POST['escalate'])) {

    $id = $_POST['incident_id'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    mysqli_query($conn, "
        UPDATE incident_reports
        SET status = 'Escalated',
            description = CONCAT(description, '\n\n[ESCALATION REASON] ', '$reason')
        WHERE id = $id
    ");

    /* OPTIONAL: log escalation into audit_logs if you have it */
    mysqli_query($conn, "
        INSERT INTO activity_logs (user_id, action, task_id)
        SELECT user_id, CONCAT('ESCALATED INCIDENT ID: ', id), id
        FROM incident_reports
        WHERE id = $id
    ");
}

/* FETCH DATA */
$data = mysqli_query($conn, "
SELECT incident_reports.*, users.username
FROM incident_reports
INNER JOIN users ON users.id = incident_reports.user_id
ORDER BY date_reported DESC
");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <h1>🚨 Incident Response Center</h1>

            <div class="glass" style="padding:20px;margin-top:20px;">
                <div class="table-container">
                    <table border="1" width="100%" cellpadding="8">

                        <tr>
                            <th>User</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Proof</th>
                            <th>Actions</th>
                        </tr>

                        <?php while ($i = mysqli_fetch_assoc($data)) { ?>

                            <tr>

                                <td><?= $i['username'] ?></td>

                                <td><?= $i['title'] ?></td>

                                <td style="max-width:250px;">
                                    <?= nl2br($i['description']) ?>
                                </td>

                                <td>
                                    <?php
                                    if ($i['severity'] == 'High') echo "🔴 High";
                                    elseif ($i['severity'] == 'Medium') echo "🟡 Medium";
                                    elseif ($i['severity'] == 'Low') echo "🟢 Low";
                                    else echo $i['severity'];
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($i['status'] == 'Resolved') echo "🟢 Resolved";
                                    elseif ($i['status'] == 'Escalated') echo "🔴 Escalated";
                                    elseif ($i['status'] == 'Under Review') echo "🟡 Under Review";
                                    else echo "⚪ Pending";
                                    ?>
                                </td>

                                <td>
                                    <?php if (!empty($i['proof_image'])) { ?>
                                        <a href="../uploads/<?= $i['proof_image'] ?>" target="_blank">View</a>
                                    <?php } else {
                                        echo "No proof";
                                    } ?>
                                </td>

                                <td>

                                    <!-- UPDATE FORM -->
                                    <form method="POST" style="margin-bottom:10px;">
                                        <input type="hidden" name="incident_id" value="<?= $i['id'] ?>">

                                        <select name="status">
                                            <option value="Under Review">Under Review</option>
                                            <option value="Resolved">Resolved</option>
                                        </select>

                                        <input type="text" name="notes" placeholder="Admin note">

                                        <button type="submit" name="update_incident">
                                            Update
                                        </button>
                                    </form>

                                    <!-- ESCALATE FORM -->
                                    <form method="POST">
                                        <input type="hidden" name="incident_id" value="<?= $i['id'] ?>">

                                        <input type="text" name="reason" placeholder="Escalation reason" required>

                                        <button type="submit" name="escalate" style="color:red;">
                                            Escalate 🚨
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        <?php } ?>

                    </table>

                </div>

            </div>
        </div>

</body>

</html>