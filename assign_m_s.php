<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;include 'database.php'; 

if ($_SESSION['role'] !== 'advisor') {
    header("Location: index.php");
    exit;

}

$mentors_result = mysqli_query($conn, "SELECT u.ID, u.name  FROM USER u INNER JOIN Mentor m ON u.ID = m.ID");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mentor_id = $_POST['mentor_id'];
    $student_ids = explode(',', $_POST['student_ids']);

    $studentcount = 0;

    foreach ($student_ids as $sid) {
        $sid = trim($sid);
    
        $check = mysqli_query($conn, "SELECT ID FROM Student WHERE ID = '$sid'");
        
        if (mysqli_num_rows($check) > 0) {
            mysqli_query($conn, "UPDATE Student SET mentor_id = '$mentor_id' WHERE ID = '$sid'");
            $studentcount++;
        }
    }

    if ($studentcount == 0) {
        $msg = "No valid student IDs provided.";
    } elseif ($studentcount == 1) {
        $msg = "Assigned <strong>$studentcount</strong> student to Mentor ID <strong>$mentor_id</strong>.";
    } else {
        $msg = "Assigned <strong>$studentcount</strong> students to Mentor ID <strong>$mentor_id</strong>.";
    }
    

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAA Portal - All Students</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/assign_m_s.css">
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
                        <li><a href="announcement_cre.php">Create Announcements</a></li>
                        <li><a href="add_drop.php">Add or Drop</a></li>

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

    <body>
        <div class="assign-container">
            <h2 class="title">Assign Students to Mentor</h2>

            <?php if (isset($msg)) echo "<p class='success-msg'>$msg</p>"; ?>

            <form method="post" class="assign-form">
                <div class="form-group">
                    <label for="mentor_id">Select Mentor</label>
                    <select name="mentor_id" id="mentor_id" required>
                        <option value="">-- Choose a Mentor --</option>
                        <?php while ($row = mysqli_fetch_assoc($mentors_result)): ?>
                            <option value="<?= $row['ID']; ?>">
                                <?=$row['name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    </select>
                </div>

                <div class="form-group">
                    <label for="student_ids">Student IDs</label>
                    <textarea name="student_ids" id="student_ids" placeholder="3601,3440" rows="4" required></textarea>
                </div>

                <button type="submit">Assign Now</button>
            </form>
        </div>
    </body>


    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>