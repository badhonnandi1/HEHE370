<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 2001;
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $upload_date = date('Y-m-d');

    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
        $file_tmp = $_FILES['resource_file']['tmp_name'];
        $file_name = $_FILES['resource_file']['name'];
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . time() . '_' . $file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            $sql1 = "INSERT INTO Resources (description, title, file, upload_at)
                     VALUES ('$description', '$title', '$file_path', '$upload_date')";

            if (mysqli_query($conn, $sql1)) {
                $last_id = mysqli_insert_id($conn);  

                $sql2 = "INSERT INTO m_update_res (m_id, res_id)
                         VALUES ({$_SESSION['user_id']}, $last_id)";

                if (mysqli_query($conn, $sql2)) {
                    $success = "Resource uploaded and linked to mentor successfully!";
                } else {
                    $error = "Failed to link resource to mentor.";
                }
            } else {
                $error = "Failed to upload resource.";
            }
        } else {
            $error = "File move failed.";
        }
    } else {
        $error = "Invalid file.";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Upload Resource</title>
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
                <li><a href="m_my_students.php">All Student List</a></li>
                <li><a href="mentor_list_all.php">Mentors</a></li>
                <li><a href="add_drop.php">Add or Drop</a></li>
                <li><a href="announcement_show.php">Announcements</a></li>
                <?php if (!isset($_SESSION['role'])): ?>
                    <li><a href="login.php">Login</a></li>
                <?php else: ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
                <li><a href="show_profile.php">My Profile</a></li>
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

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Resource Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required></textarea>

        <label for="resource_file">Upload File:</label>
        <input type="file" name="resource_file" id="resource_file" required accept=".pdf,.docx,.pptx,.zip,.jpg,.png,.mp4">

        <button type="submit">Upload Resource</button>
    </form>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
