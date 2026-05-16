<?php
session_start();
require_once(__DIR__ . '/../config/connect.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = "";

if (isset($_POST['incident-reports'])) {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $severity = mysqli_real_escape_string($conn, $_POST['severity']);

    $imageName = "";

    // IMAGE UPLOAD
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == 0) {

        $allowed = ['jpg', 'jpeg', 'png'];

        $fileName = $_FILES['proof_image']['name'];
        $tmpName = $_FILES['proof_image']['tmp_name'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {

            $imageName = time() . "_" . rand(1000, 9999) . "." . $ext;

            $uploadPath = __DIR__ . "/../uploads/" . $imageName;

            move_uploaded_file($tmpName, $uploadPath);
        } else {
            $message = "Only JPG, JPEG, and PNG files are allowed.";
        }
    }

    // SAVE REPORT
    if ($message == "") {

        mysqli_query($conn, "
            INSERT INTO incident_reports
            (user_id, title, description, severity, status, proof_image, date_reported)
            VALUES
            (
                '$user_id',
                '$title',
                '$description',
                '$severity',
                'Pending',
                '$imageName',
                NOW()
            )
        ");

        // ACTIVITY LOG
        mysqli_query($conn, "
            INSERT INTO activity_logs
            (user_id, action, log_time)
            VALUES
            (
                '$user_id',
                'Submitted incident report',
                NOW()
            )
        ");

        $message = "Incident report submitted successfully.";
    }
}
?>



<!DOCTYPE html>
<html>

<?php include('../includes/header.php'); ?>



<body>

    <div class="container dashboard">

        <?php include('sidebar.php'); ?>

        <div class="main-content glass">

            <?php
            $currentSection = 'Submit Report';
            ?>

            <div class="section-bar">
                <div class="section-name"><?= htmlspecialchars($currentSection) ?></div>
            </div>

            <h1>🚨 Submit Incident Report</h1>

            <?php if ($message != "") { ?>
                <div class="<?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                    <?= $message ?>
                </div>
            <?php } ?>

            <div class="report-card glass">

                <form method="POST" enctype="multipart/form-data">

                    <!-- TITLE -->
                    <div class="input-group">
                        <label>Title</label>
                        <input type="text" name="title" placeholder="Enter report title" required>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="description" rows="6" placeholder="Describe the incident..." required></textarea>
                    </div>

                    <!-- SEVERITY -->
                    <div class="input-group">
                        <label>Severity</label>
                        <select name="severity" required>
                            <option value="">Select severity</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>

                    <!-- IMAGE -->
                    <div class="input-group">
                        <label>Upload Proof Image</label>
                        <input type="file" name="proof_image" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
                        <img id="preview" class="preview-image">
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" name="submitReport" class="btn full">
                        Submit Report
                    </button>

                </form>

            </div>

        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(event.target.files[0]);
            preview.style.display = "block";
        }
    </script>

</body>

</html>