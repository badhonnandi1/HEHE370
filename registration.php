<?php
include 'database.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    $profilePic = $_FILES['profile_pic']['tmp_name'];
    $profilePicContent = addslashes(file_get_contents($profilePic));

    $check = mysqli_query($conn, "SELECT id FROM USER WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists. Please use a different email.";
    } else {
        $hashedPassword = $password;
        mysqli_query($conn, "INSERT INTO USER (name, email, password, profile_pic) 
                             VALUES ('$name', '$email', '$hashedPassword', '$profilePicContent')");

        $userId = mysqli_insert_id($conn);

        if ($role === "Student") {
            mysqli_query($conn, "INSERT INTO Student (id) VALUES ($userId)");
        } elseif ($role === "Mentor") {
            mysqli_query($conn, "INSERT INTO Mentor (id) VALUES ($userId)");
        } elseif ($role === "Advisor") {
            mysqli_query($conn, "INSERT INTO Advisor (id) VALUES ($userId)");
        }

        header("Location: login.php");
        exit();
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="registration.css">

</head>

<body>
    <div class="register-container">
        <h2>Register</h2>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" action="registration.php" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label for="profile_pic">Profile Picture:</label>
            <input type="file" name="profile_pic" accept="image/*" required>


            <label>Role:</label>
            <select name="role" required>
                <option value="">--Select Role--</option>
                <option value="Student">Student</option>
                <option value="Mentor">Mentor</option>
                <option value="Advisor">Advisor</option>
            </select>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </form>
    </div>
</body>

</html>