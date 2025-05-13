<?php
session_start();
require_once('database.php');

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $Dept = $_POST['Dept'];
    $Reg_No = $_POST['Reg_No'];

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['size'] > 0) {
        $imgData = addslashes(file_get_contents($_FILES['profile_pic']['tmp_name']));
        $updateQuery = "UPDATE USER SET Name='$name', Email='$email', Password='$password', Profile_pic='$imgData',phone = '$phone', address='$address' WHERE id='$id'";
    } else {
        $updateQuery = "UPDATE USER SET Name='$name', Email='$email', Password='$password',phone = '$phone', address='$address' WHERE id='$id'";
    }
    mysqli_query($conn, $updateQuery);

    header("Location: show_profile.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM USER WHERE id='$id'");
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/edit_profile.css">
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
            <h2>Edit Profile</h2>
            <form method="POST" enctype="multipart/form-data">
                <label>Name:</label>
                <input type="text" name="name" value="<?= $row['Name'] ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?= $row['Email'] ?>" required>

                <label>Password:</label>
                <input type="text" name="password" value="<?= $row['Password'] ?>" required>

                <label>Phone:</label>
                <input type="text" name="phone" value="<?= $row['phone'] ?>" required>

                <label>Address:</label>
                <input type="text" name="address" value="<?= $row['address'] ?>" required>


                <label for="profile_pic">Profile Picture:</label>
                <input type="file" name="profile_pic" accept="image/*">


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