<?php
session_start();
include(__DIR__ . '/../config/connect.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

/* =========================
   CREATE POLICY
========================= */
if (isset($_POST['create_policy'])) {

    $name = mysqli_real_escape_string($conn, $_POST['policy_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "
        INSERT INTO policies (policy_name, description, status)
        VALUES ('$name', '$desc', 'Inactive')
    ");

    header("Location: policies.php?success=created");
    exit();
}

/* =========================
   TOGGLE POLICY STATUS
========================= */
if (isset($_GET['toggle'])) {

    $id = intval($_GET['toggle']);

    $res = mysqli_query($conn, "SELECT status FROM policies WHERE id=$id");

    if ($res && mysqli_num_rows($res) > 0) {

        $row = mysqli_fetch_assoc($res);

        $newStatus = ($row['status'] === 'Active') ? 'Inactive' : 'Active';

        mysqli_query($conn, "
            UPDATE policies
            SET status='$newStatus'
            WHERE id=$id
        ");
    }

    header("Location: policies.php");
    exit();
}

/* =========================
   ASSIGN POLICY TO USER
========================= */
if (isset($_POST['assign_policy'])) {

    $user_id = intval($_POST['user_id']);
    $policy_id = intval($_POST['policy_id']);

    // prevent duplicate assignment
    $check = mysqli_query($conn, "
        SELECT id FROM user_policies
        WHERE user_id=$user_id AND policy_id=$policy_id
    ");

    if (mysqli_num_rows($check) == 0) {

        mysqli_query($conn, "
            INSERT INTO user_policies (user_id, policy_id)
            VALUES ($user_id, $policy_id)
        ");
    }

    header("Location: policies.php?success=assigned");
    exit();
}

/* =========================
   DATA FETCH
========================= */
$policies = mysqli_query($conn, "SELECT * FROM policies ORDER BY id DESC");
$users = mysqli_query($conn, "SELECT id, username FROM users");
?>

<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>

<body>

<div class="container dashboard">

<?php include('sidebar.php'); ?>

<div class="main-content glass">

<h1>🛡 Policy Management</h1>
<p style="opacity:0.7;">Create, activate, and assign compliance policies</p>

<!-- SUCCESS MESSAGES -->
<?php if (isset($_GET['success']) && $_GET['success'] == 'created') { ?>
    <div class="success-box">✔ Policy created successfully!</div>
<?php } ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 'assigned') { ?>
    <div class="success-box">✔ Policy assigned to employee successfully!</div>
<?php } ?>

<!-- =========================
     CREATE POLICY
========================= -->
<div class="glass" style="padding:20px;margin-top:20px;">

    <h3>Create New Policy</h3>

    <form method="POST">

        <input type="text" name="policy_name" placeholder="Policy Name" required
               style="width:100%;padding:10px;margin-bottom:10px;">

        <textarea name="description" placeholder="Description" required
                  style="width:100%;padding:10px;margin-bottom:10px;"></textarea>

        <button type="submit" name="create_policy"
                style="background:#00ff99;color:#000;padding:10px 15px;border:none;border-radius:6px;font-weight:bold;">
            + Create Policy
        </button>

    </form>

</div>

<!-- =========================
     ASSIGN POLICY
========================= -->
<div class="glass" style="padding:20px;margin-top:20px;">

    <h3>Assign Policy to Employee</h3>

    <form method="POST">

        <!-- USER -->
        <select name="user_id" required style="width:100%;padding:10px;margin-bottom:10px;">
            <option value="">Select Employee</option>
            <?php while ($u = mysqli_fetch_assoc($users)) { ?>
                <option value="<?= $u['id'] ?>">
                    <?= $u['username'] ?> (<?= $u['username'] ?>)
                </option>
            <?php } ?>
        </select>

        <!-- POLICY -->
        <select name="policy_id" required style="width:100%;padding:10px;margin-bottom:10px;">
            <option value="">Select Policy</option>
            <?php
            $plist = mysqli_query($conn, "SELECT * FROM policies");
            while ($p = mysqli_fetch_assoc($plist)) {
            ?>
                <option value="<?= $p['id'] ?>">
                    <?= $p['policy_name'] ?> (<?= $p['status'] ?>)
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="assign_policy"
                style="background:#00ccff;color:#000;padding:10px 15px;border:none;border-radius:6px;font-weight:bold;">
            + Assign Policy
        </button>

    </form>

</div>

<!-- =========================
     POLICY TABLE
========================= -->
<div class="glass" style="padding:20px;margin-top:20px;overflow-x:auto;">

<table style="width:100%;color:white;border-collapse:collapse;min-width:800px;">

<tr style="text-align:left;border-bottom:1px solid rgba(255,255,255,0.2);">
    <th>Policy Name</th>
    <th>Description</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($p = mysqli_fetch_assoc($policies)) { ?>

<tr style="border-bottom:1px solid rgba(255,255,255,0.1);">

    <td><?= htmlspecialchars($p['policy_name']) ?></td>
    <td><?= htmlspecialchars($p['description']) ?></td>

    <td>
        <?php if ($p['status'] === 'Active') { ?>
            <span style="color:#00ff99;font-weight:bold;">ACTIVE</span>
        <?php } else { ?>
            <span style="color:#ff4d4d;font-weight:bold;">INACTIVE</span>
        <?php } ?>
    </td>

    <td>
        <a href="policies.php?toggle=<?= $p['id'] ?>"
           onclick="return confirm('Toggle this policy status?');"
           style="color:#00ccff;">
           Toggle
        </a>
    </td>

</tr>

<?php } ?>

</table>

</div>

</div>
</div>

</body>
</html>