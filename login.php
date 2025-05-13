<?php
session_start();
include 'database.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];


    $result = mysqli_query($conn, "SELECT * FROM USER WHERE Email = '$email' AND Password = '$password'");
    
    // echo mysqli_num_rows($result) === 1;

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result); // associative array niye astese eita key: value pair dictionary er moto
        $userId = $user['ID'];

        $role = '';
        if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Student WHERE ID = $userId")) === 1) {
            $role = 'student';
        } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Mentor WHERE ID = $userId")) === 1) {
            $role = 'mentor';
        } elseif (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM Advisor WHERE ID = $userId")) === 1) {
            $role = 'advisor';
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $user['Name'];

        header("Location: index.php");
        exit();    
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="CSS/logre.css">
<title>Login</title>
</head>
<body>
    <div class="login-container">
        <h4>Welcome to OAA Student <br>Management System</h4><br>
        <h2>Login</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>

    
</body>
</html>
