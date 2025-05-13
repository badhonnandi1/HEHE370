<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mentor') {
    header("Location: login.php");
    exit();
}

$mentor_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['res_id'])) {
    $res_id = (int)$_POST['res_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $upload_date = date('Y-m-d H:i:s');

    $check_stmt = $conn->prepare("SELECT r.file FROM Resources r 
        JOIN m_update_res m ON r.res_id = m.res_id 
        WHERE r.res_id = ? AND m.m_id = ?");
    $check_stmt->bind_param("ii", $res_id, $mentor_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $old_file_path = $row['file'];
        $new_file_path = $old_file_path;

        if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $new_file_name = time() . '_' . basename($_FILES['resource_file']['name']);
            $new_file_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $new_file_path)) {
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            } else {
                $error = "New file upload failed.";
            }
        }

        if (!$error) {
            $update_stmt = $conn->prepare("UPDATE Resources SET title = ?, description = ?, file = ?, upload_at = ? WHERE res_id = ?");
            $update_stmt->bind_param("ssssi", $title, $description, $new_file_path, $upload_date, $res_id);

            if ($update_stmt->execute()) {
                $success = "Resource updated successfully!";
            } else {
                $error = "Database error during update.";
            }
        }
    } else {
        $error = "You are not authorized to update this resource.";
    }
}

// Fetch mentor's resources
$stmt = $conn->prepare("SELECT r.res_id, r.title FROM Resources r JOIN m_update_res m ON r.res_id = m.res_id WHERE m.m_id = ?");
$stmt->bind_param("i", $mentor_id);
$stmt->execute();
$resources = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Resource</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="update_resources.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Update Resource</h1>
        <nav>
            <ul>
                <li><a href="mentor_dashboard.php">Dashboard</a></li>
                <li><a href="view_requests.php">View Requests</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="form-box">
    <?php if ($success): ?>
        <p class="message"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($resources->num_rows > 0): ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="res_id">Select Resource:</label>
            <select name="res_id" id="res_id" required>
                <option value="">-- Select Resource --</option>
                <?php while ($row = $resources->fetch_assoc()): ?>
                    <option value="<?= $row['res_id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="title">New Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">New Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="resource_file">Upload New File (optional):</label>
            <input type="file" name="resource_file" id="resource_file" accept=".pdf,.docx,.pptx,.zip,.jpg,.png,.mp4">

            <button type="submit">Update Resource</button>
        </form>
    <?php else: ?>
        <p>No resources found to update.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
