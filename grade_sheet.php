<?php
session_start();
include 'database.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$student_id = $_SESSION['user_id'];

$sql = "SELECT course_name, mid_marks, final_marks, quiz_marks FROM Grade_sheet WHERE s_id = $student_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Grade Sheet</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="CSS/grade.css">

</head>

<body>
    <header>
        <div class="container">
        <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
        <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">My Advisor</a></li>
                    <li><a href="#">Student List</a></li>
                    <li><a href="#">Lets Chat</a></li>
                    <li><a href="grade_sheet.php">Grade Sheet</a></li>

                    <li><a href="resources.php">Resources</a></li>
                    <li><a href="announcement_show.php">Announcements</a></li>
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
    <div class="grade-container">
        <h2>My Grade Sheet - First Semester</h2>
        <table>
            <tr>
                <th>Course</th>
                <th>Mid</th>
                <th>Final</th>
                <th>Quiz</th>
                <th>Attendance</th>
                <th>Total</th>
                <th>CGPA</th>
                <th>Grade</th>

            </tr>

            <?php
            $overall_cgpa = 0.00;
            $course_count = 0;

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $course_count++;
  

                    $total = $row['mid_marks'] + $row['final_marks'] + $row['quiz_marks'] +5;
                    $cgpa = 0.00;
                    $grade_letter = '';
                    if ($total >= 90) {
                        $cgpa = 4.00;
                        if ($total >= 97) {
                            $grade_letter = 'A+';
                        } else {
                            $grade_letter = 'A';
                        }
                    } elseif ($total >= 85) {
                        $cgpa = 3.7;
                        $grade_letter = 'A-';
                    } elseif ($total >= 80) {
                        $cgpa = 3.3;
                        $grade_letter = 'B+';
                    } elseif ($total >= 75) {
                        $cgpa = 3.00;
                        $grade_letter = 'B'; 
                    } elseif ($total >= 70) {
                        $cgpa = 2.7;
                        $grade_letter = 'B-';
                    } elseif ($total >= 65) {
                        $cgpa = 2.3;
                        $grade_letter = 'C+';
                    } elseif ($total >= 60) {
                        $cgpa = 2.00;
                        $grade_letter = 'C';
                    } elseif ($total >= 57) {
                        $cgpa = 1.7;
                        $grade_letter = 'C-';
                    } elseif ($total >= 55) {
                        $cgpa = 1.3;
                        $grade_letter = 'D+';
                    } elseif ($total >= 52) {
                       $cgpa = 1.0;   
                           $grade_letter = 'D';  
                    } elseif ($total >= 50) {
                        $cgpa = 0.7;
                        $grade_letter = 'D-';
                    } else {
                        $cgpa = 0.00;
                        $grade_letter = 'F';
                    }
                    $total = number_format($total, 2);
                    $cgpa = number_format($cgpa, 2);
                    $overall_cgpa += $cgpa;

                    echo "<tr>
                        <td>{$row['course_name']}</td>
                        <td>{$row['mid_marks']}</td>
                        <td>{$row['final_marks']}</td>
                        <td>{$row['quiz_marks']}</td>
                        <td>5</td>

                        <td>$total</td>
                        <td>$cgpa</td>
                        <td>$grade_letter</td>

                      </tr>";
                }
                $overall_cgpa = number_format($overall_cgpa / $course_count, 2);
                echo "<tr>
                        <td colspan='6' class='total'>Overall CGPA</td>
                        <td colspan='2' class='total'>$overall_cgpa</td>
                      </tr>";
            } else {
                echo "<tr><td colspan='7' class='no-data'>No grades found.</td></tr>";
            }
            echo "<tr>
                <td colspan='8' class='submit-btn'>
                    <a href='update_gradesheet.php' class='btn'>Modify Grade Sheet</a>
                </td>
                  </tr>";
            mysqli_close($conn);
            ?>
        </table>
    </div>
</body>
<footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>

</html>