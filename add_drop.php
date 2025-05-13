<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
include 'database.php';

if ($role !== 'advisor') {
    header("Location: index.php");
    exit;
}

$message = '';

if (isset($_POST['create_user'])) {
    $role_new = $_POST['role']; // na use korleo hoy
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $checkSql = "SELECT * FROM USER WHERE Email = '$email'";
    $result = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($result) > 0) {
        $message = "<p class='message error'>Email already exists. Please use a different email.</p>";
        
    }else{
        $sql = "INSERT INTO USER (Email,Password,Name) VALUES ('$email', '$password','$name')";
        if (mysqli_query($conn, $sql)) {
            $userId = mysqli_insert_id($conn);
            if ($role_new === "student") {
                $studentSql = "INSERT INTO Student (ID) VALUES ('$userId')";
                mysqli_query($conn, $studentSql);
            } elseif ($role_new === "mentor") {
                $mentorSql = "INSERT INTO Mentor (ID) VALUES ('$userId')";
                mysqli_query($conn, $mentorSql);
            }
    
            $message = "<p class='message'>Dear Advisor, User created successfully with role: <strong>$role_new</strong>!</p>";
        } else {
            $message = "<p class='message error'> Error: " . mysqli_error($conn) . "</p>";
        }
    }

}

if (isset($_POST['delete_user'])) {
    $email = $_POST['delete_email'];
    
    $checkSql = "SELECT * FROM USER WHERE Email = '$email'";
    $result = mysqli_query($conn, $checkSql);
    

    
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
        $userId = $row['ID'];


        mysqli_query($conn, "DELETE FROM Mentor WHERE ID = $userId");
        mysqli_query($conn, "DELETE FROM Student WHERE ID = $userId");

        $sql = "DELETE FROM USER WHERE Email = '$email'";
        if (mysqli_query($conn, $sql)) {
            $message = "<p class='message'>User deleted successfully!</p>";
        } else {
            $message = "<p class='message error'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        $message = "<p class='message error'>No user found with that email.</p>";
    }
}
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Add or Drop User</title>
    <link rel="stylesheet" href="styles.css">

    <link rel="stylesheet" href="CSS/add_drop.css">

</head>

<body>
    <header>
        <div class="container">
            <h1>OFFICE OF ACADEMIC ACTIVITIES
            </h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="student_list_all.php">All Student List</a></li>
                    <li><a href="mentor_list_all.php">Mentors</a></li>


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

    <div class="admin-container">
        <div class="admin-box">
            <h2>Create New User</h2>
            <form method="POST">
                <input type="hidden" name="action" value="create">

                <label for="role">Role</label>
                <select name="role" id="role" required>
                    <option value="">-- Select Role --</option>
                    <option value="student">Student</option>
                    <option value="mentor">Mentor</option>
                </select>

                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" name="create_user" class="submit-btn">Create User</button>
            </form>

        </div>


        <div class="admin-box">
            <h2>Delete User</h2>
            <form method="POST">
                <label for="delete_email">User Email</label>
                <input type="email" name="delete_email" id="delete_email" required>

                <button type="submit" name="delete_user" class="submit-btn" style="background: #dc3545;">Delete User</button>
            </form>

        </div>
    </div>

    <?php if (isset($message)) : ?>
        <div class="message-block">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <footer>
        <div class="container">
            <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>