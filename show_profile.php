<?php
session_start();
require_once('database.php');
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// $id = $_SESSION['user_id'];
// $result3 = mysqli_query($conn, "SELECT * FROM USER WHERE id='$id'");
// $row3 = mysqli_fetch_array($result3);  
// echo $row3['Name'];
// echo $row3['Email'];
// echo $row3['Password'];
// echo $row3['Profile_pic'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo 'H';

    // while ($row3 = mysql_fetch_array($result3)) {
    //     $fname = $row3['Name'];
    //     $lname = $row3['Email'];
    //     $address = $row3['Password'];
    //     $contact = $row3['contact'];
    //     $picture = $row3['Profile_pic'];

    // }    
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAA Portal</title>
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="CSS/show_profile.css">
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


    <?php
    $id = $_SESSION['user_id'];
    $result3 = mysqli_query($conn, "SELECT * FROM USER WHERE id='$id'");
    $row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);

    $profileImage = $row3['Profile_pic'] ? 'data:image/jpeg;base64,' . base64_encode($row3['Profile_pic']) : 'default.jpg';
    ?>
    <section class="profile-section">
        <div class="profile-card">
            <div class="profile-image">
                <img src="<?= $profileImage ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h2><?= htmlspecialchars($row3['Name']) ?></h2>
                <p><strong>Email:</strong> <?= htmlspecialchars($row3['Email']) ?></p>
                <p><strong>Password:</strong> <?= htmlspecialchars($row3['Password']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($row3['phone']) ?></p>
                <p><strong>Adress:</strong> <?= htmlspecialchars($row3['address']) ?></p>


                <p><strong>I am a <?= htmlspecialchars($role) ?></p></strong>
                <a class="edit-button" href="edit_profile.php">Edit Profile</a>
            </div>
        </div>
    </section>


    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>