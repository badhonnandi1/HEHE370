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

// DELETE RESOURCE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $res_id = (int)$_POST['res_id'];

    $check_stmt = $conn->prepare("SELECT r.file FROM Resources r 
                                  JOIN m_update_res m ON r.res_id = m.res_id 
                                  WHERE r.res_id = ? AND m.m_id = ?");
    $check_stmt->bind_param("ii", $res_id, $mentor_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $file_path = $result->fetch_assoc()['file'];

        $del_link = $conn->prepare("DELETE FROM m_update_res WHERE res_id = ? AND m_id = ?");
        $del_link->bind_param("ii", $res_id, $mentor_id);
        $del_link->execute();

        $del_res = $conn->prepare("DELETE FROM Resources WHERE res_id = ?");
        $del_res->bind_param("i", $res_id);
        $del_res->execute();

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        $success = "Resource deleted successfully.";
    } else {
        $error = "You are not authorized to delete this resource.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
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

    if ($result && $result->num_rows === 1) {
        $old_file_path = $result->fetch_assoc()['file'];
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
            $update_stmt->execute();
            $success = "Resource updated successfully.";
        }
    } else {
        $error = "You are not authorized to update this resource.";
    }
}

$res_stmt = $conn->prepare("SELECT r.res_id, r.title FROM Resources r 
                            JOIN m_update_res m ON r.res_id = m.res_id 
                            WHERE m.m_id = ?");
$res_stmt->bind_param("i", $mentor_id);
$res_stmt->execute();
$resources = $res_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Resources</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="update_resources.css">
</head>
<body>
<header>
  <div class="container">
    <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="m_my_students.php">My Students</a></li>
        <li><a href="group_chat.php">Lets Chat</a></li>
        <li><a href="manage_resources.php">Manage Requests</a></li>
        <li><a href="upload_resource.php">Upload Requests</a></li>

        <li><a href="announcement_cre.php">Give Announcements</a></li>
        <li><a href="announcement_show.php">Announcements</a></li>
        <li><a href="show_profile.php">My Profile</a></li>
        <?php if (!$role): ?>
          <li><a href="login.php">Login</a></li>
        <?php else: ?>
          <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>


      </ul>
    </nav>
  </div>
</header>

<div class="form-box">
    <?php if ($success): ?>
        <p class="message"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($resources && $resources->num_rows > 0): ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="res_id">Select Resource:</label>
            <select name="res_id" id="res_id" required>
                <option value="">-- Select Resource --</option>
                <?php while ($row = $resources->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['res_id']) ?>">
                        <?= htmlspecialchars($row['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="title">New Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">New Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="resource_file">Upload New File (optional):</label>
            <input type="file" name="resource_file" id="resource_file" accept=".pdf,.docx,.pptx,.zip,.jpg,.png,.mp4">

            <div class="button-group">
                <button type="submit" name="action" value="update">Update Resource</button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Are you sure you want to delete this resource?');">
                    Delete Resource
                </button>
            </div>
        </form>
    <?php else: ?>
        <p>No resources found to manage.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
