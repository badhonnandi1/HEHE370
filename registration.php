<?php
include 'database.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $fac_key  = $_POST['fac_key'];

    $profilePic = $_FILES['profile_pic']['tmp_name'];
    $profilePicContent = addslashes(file_get_contents($profilePic));

    $fac_check = mysqli_query($conn, "SELECT 'key' FROM Faculty WHERE 'key' = '$fac_key'");
    if (mysqli_num_rows($fac_check) === 0) {
        $error = "Invalid faculty key. Please enter a valid One.";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM USER WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            mysqli_query($conn, "INSERT INTO USER (name, email, password, profile_pic) VALUES ('$name', '$email', '$password', '$profilePicContent')");

            $userId = mysqli_insert_id($conn);

            mysqli_query($conn, "INSERT INTO Advisor (id) VALUES ($userId)");

            header("Location: login.php");
            exit();
        }
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="CSS/registration.css">

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


            <label>Faculty Key to Verify:</label>
            <input type="password" name="fac_key" required>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </form>
    </div>
</body>

</html>