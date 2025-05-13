<?php
session_start();
include 'database.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_name = $_POST['course_name'];
    $final_marks = $_POST['final_marks'];
    $mid_marks = $_POST['mid_marks'];
    $quiz_marks = $_POST['quiz_marks'];
    $attendance = $_POST['attendance'];

    $checkQuery = "SELECT * FROM Grade_sheet WHERE s_id='$id' AND course_name='$course_name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $updateQuery = "UPDATE Grade_sheet SET final_marks='$final_marks', mid_marks='$mid_marks',  quiz_marks='$quiz_marks', Attendance='$attendance' WHERE s_id='$id' AND course_name='$course_name'";

        mysqli_query($conn, $updateQuery);
    } else {
        $insertQuery = "INSERT INTO Grade_sheet (s_id, course_name, final_marks, mid_marks, quiz_marks, Attendance) VALUES ('$id', '$course_name', '$final_marks', '$mid_marks', '$quiz_marks', '$attendance')";

        mysqli_query($conn, $insertQuery);
    }

    header("Location: grade_sheet.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="CSS/u_grade.css">
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

    <section class="edit-section">
        <div class="edit-form-card">
            <h2>Update Grade Sheet</h2>
            <form method="POST" enctype="multipart/form-data" action="update_gradesheet.php">

                <label for="course_name">Select Course:</label>
                <select name="course_name" id="course_name" required>
                    <option value="">-- Select a course --</option>
                    <option value="CSE110">CSE110</option>
                    <option value="ENG101">ENG101</option>
                    <option value="ENG091">ENG091</option>
                    <option value="MAT092">MAT092</option>
                    <option value="MAT110">MAT110</option>
                    <option value="PHY111">PHY111</option>
                </select>

                <!-- Final Marks -->
                <label for="final_marks">Final Marks:</label>
                <input type="number" name="final_marks" required>

                <!-- Mid Marks -->
                <label for="mid_marks">Mid Marks:</label>
                <input type="number" name="mid_marks" id="mid_marks" required>

                <!-- Quiz Marks -->
                <label for="quiz_marks">Quiz Marks:</label>
                <input type="number" name="quiz_marks" required>

                <!-- Attendance -->
                <label for="attendance">Attendance:</label>
                <input type="number" name="attendance" required>

                <!-- Submit Button -->
                <button class="save-button" type="submit">Save</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>