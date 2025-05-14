<?php
session_start();
include 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$student_id = $_SESSION['user_id'];

$mentor_query = mysqli_query($conn, "SELECT mentor_id FROM Student WHERE ID = '$student_id'");
$mentor_row = mysqli_fetch_assoc($mentor_query);
$mentor_id = $mentor_row ? $mentor_row['mentor_id'] : null;

$resources = [];

if ($mentor_id) {
    $resource_query = "
        SELECT r.title, r.description, r.file, r.upload_at
        FROM Resources r
        JOIN m_update_res mur ON r.res_id = mur.res_id
        WHERE mur.m_id = '$mentor_id'
        ORDER BY r.upload_at DESC
    ";

    $result = mysqli_query($conn, $resource_query);
    while ($row = mysqli_fetch_assoc($result)) {
        $resources[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="view_resources.css">
</head>

<body>

    <header>
        <div class="container">
        <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
        <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="advisors.php">My Advisor</a></li>
                    <li><a href="group_chat.php">Lets Chat</a></li>
                    <li><a href="grade_sheet.php">Grade Sheet</a></li>

                    <li><a href="view_resource.php">Resources</a></li>
                    <li><a href="announcement_show.php">Announcements</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container1">
        <h2>Uploaded Resources</h2>
        <?php if ($mentor_id && count($resources) > 0): ?>
            <ul class="resource-list">
                <?php foreach ($resources as $res): ?>
                    <li class="resource-item">
                        <h3><?= htmlspecialchars($res['title']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($res['description'])) ?></p>
                        <p class='time'><strong>Uploaded on:</strong> <?= $res['upload_at'] ?></p>
                        <a href="<?= htmlspecialchars($res['file']) ?>" download class="download-btn">Download</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif ($mentor_id): ?>
            <p>No resources uploaded by your mentor yet.</p>
        <?php else: ?>
            <p>No mentor assigned. Please contact admin.</p>
        <?php endif; ?>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 OAA Management System</p>
        </div>
    </footer>

</body>

</html>