<?php
session_start();
require_once('database.php');
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

if ($role !== 'advisor') {
    header("Location: index.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAA Portal - All Students</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/show_std_mnt.css">
</head>

<body>
    <header>
        <div class="container">
            <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="student_list_all.php">All Student List</a></li>
                    <li><a href="mentor_list_all.php ">Mentors</a></li>
                    <li><a href="add_drop.php">Add or Drop</a></li>
                    <li><a href="announcement_cre.php">Create Announcements</a></li>
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
            $result = mysqli_query($conn, "SELECT * FROM USER U INNER JOIN Student S ON U.id = S.id");

            if (!$result) {
                die('Error in query: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    $profileImage = $row['Profile_pic']
                        ? 'data:image/jpeg;base64,' . base64_encode($row['Profile_pic'])
                        : 'default.jpg';
            ?>
                    <div class="profile-card">
                        <div class="profile-image">
                            <img src="<?= $profileImage ?>" alt="Profile Picture">
                        </div>
                        <div class="profile-info">
                            <h2><?= htmlspecialchars($row['Name']) ?></h2>
                            <p><strong>Email:</strong> <?= htmlspecialchars($row['Email']) ?></p>
                            <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                            <p>
                                <strong>View Grade Sheet:</strong>
                                <a href="v_student_grade_sheet.php?s_id=<?= $row['ID'] ?>" class="view-button">View</a>
                            </p>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No students found.</p>";
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