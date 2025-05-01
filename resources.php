<?php
session_start();
include 'database.php';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - University Portal</title>
    <link rel="stylesheet" href="resources.css">
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

    <section class="resources">
        <div class="container">
            <h2>Resource Center</h2>
            <div class="resource-cards">
                <div class="resource-card">
                    <h3>Lecture Notes</h3>
                    <p>Download lecture notes and presentation slides.</p>
                </div>
                <div class="resource-card">
                    <h3>Study Materials</h3>
                    <p>Access recommended textbooks and materials.</p>
                </div>
                <div class="resource-card">
                    <h3>Project Guidelines</h3>
                    <p>Find templates and instructions for academic projects.</p>
                </div>
                <div class="resource-card">
                    <h3>Previous Papers</h3>
                    <p>Review past exam questions for better preparation.</p>
                </div>
                <div class="resource-card">
                    <h3>Online Courses</h3>
                    <p>Enroll in additional online courses for skill enhancement.</p>
                </div>
                <div class="resource-card">
                    <h3>Online Courses</h3>
                    <p>Enroll in additional online courses for skill enhancement.</p>
                </div>

            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 University Portal. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
