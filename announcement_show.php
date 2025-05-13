<?php
session_start();
require_once('database.php');
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// if ($role !== 'advisor') { 
//     header("Location: index.php");
//     exit;
// }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAA Portal - All Students</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/announcement_show.css">
</head>

<body>

    <header>
        <div class="container">
            <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    
                    <?php if ($role === 'advisor' || $role === 'mentor'): ?>
                        <li><a href="student_list_all.php">All Student List</a></li>
                        <li><a href="mentor_list_all.php ">Mentors</a></li>
                        <li><a href="announcement_cre.php">Create Announcements</a></li>
                        <li><a href="add_drop.php">Add or Drop</a></li>
                    <?php endif; ?>
                    
                    <?php if ($role === 'student'): ?>
                        <li><a href="announcement_show.php">Announcements</a></li>
                        <li><a href="grade_sheet.php">Grade Sheet</a></li>
                        <li><a href="view_resource.php">Resources</a></li>
                        <li><a href="request_resource.php">Request Resources</a></li>
                        <li><a href="group_chat.php">Lets Chat</a></li>

                        <?php endif; ?>


                    <?php if (!$role): ?>
                        <li><a href="login.php">Login</a></li>
                    <?php else: ?>
                        <li><a href="logout.php">Logout</a></li>
                    <?php endif; ?>
                    <li><a href="show_profile.php">My Profile</a></li>
                </ul>

            </nav>
        </div>
    </header>

    <section class="students-section">
        <div class="students-container">
            <?php

            $user_id = $_SESSION['user_id'];
            $query = "";

            if ($role === 'advisor') {
                $query = "SELECT * FROM Announcement";



            } elseif ($role === 'mentor') {
                $query = "SELECT * FROM Announcement WHERE target_role IN ('Mentor', 'Both')";
            } elseif ($role === 'student') {

                $query = "SELECT * FROM Announcement WHERE target_role IN ('Student', 'Both')";
            } else {
                header("Location: index.php");
                exit;
            }

            $result = mysqli_query($conn, $query);

            if (!$result) {
                die('Error in query: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            ?>
                    <div class="profile-info">
                        <h3>Title: <?= htmlspecialchars($row['title']) ?></h3>
                        <p><strong>Description:</strong> <?= htmlspecialchars($row['Content']) ?></p>
                        <p><strong>Given By:</strong> <?= htmlspecialchars($row['name']) ?></p>
                        <p><strong>Given Date:</strong> <?= htmlspecialchars($row['date']) ?></p>
                    </div>
            <?php
                }
            } else {
                echo "<p>No announcements available for your role.</p>";
            }
            ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>