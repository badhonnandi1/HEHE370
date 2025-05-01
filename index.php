<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAA Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <div class="container">
            <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Announcements</a></li>
                    <li><a href="resources.php">Resources</a></li>
                    <?php if ($role === 'advisor'): ?>
                        <li><a href="admin_dashboard.php">Advisor Dashboard</a></li>
                    <?php elseif ($role === 'student'): ?>
                        <li><a href="student_dashboard.php">Student Dashboard</a></li>
                    <?php elseif ($role === 'mentor'): ?>
                        <li><a href="mentor_dashboard.php">Mentor Dashboard</a></li>
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

    <section class="hero">
        <div class="container">
            <h2>Welcome to Your Academic Journey</h2>
            <p>Manage your grades, connect with mentors, request resources, and stay updated with announcements!</p>
        </div>
    </section>


    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>