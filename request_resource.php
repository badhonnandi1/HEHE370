<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Make sure student exists
$check_student = mysqli_query($conn, "SELECT ID FROM Student WHERE ID = '$student_id'");
if (mysqli_num_rows($check_student) === 0) {
    $error = "Your student account does not exist in the database.";
}

// Fetch mentors for dropdown
$mentor_result = mysqli_query($conn, "SELECT ID FROM Mentor");
$mentors = [];
while ($row = mysqli_fetch_assoc($mentor_result)) {
    $mentors[] = $row['ID'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $type      = $_POST['type'];
    $message   = $_POST['message'];
    $mentor_id = $_POST['mentor_id'];
    $status    = "pending";
    $date      = date("Y-m-d");

    if (!$mentor_id) {
        $error = "Please select a mentor.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO Request (request_at, status, type, message, s_id, m_id) 
                                       VALUES ('$date', '$status', '$type', '$message', '$student_id', '$mentor_id')");

        if ($insert) {
            $success = "Request submitted successfully!";
        } else {
            $error = "Database Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Request Resource</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="request_resource.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Request Resource</h1>
        <nav>
            <ul>
                <li><a href="student_dashboard.php">Dashboard</a></li>
                <li><a href="view_announcement.php">Announcements</a></li>
                <li><a href="#">Chat</a></li>
                <li><a href="#">Resources</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="form-box">
    <h2>Request a Resource from Mentor</h2>
    <?php if ($success): ?>
        <p class="message"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="mentor_id">Select Mentor:</label>
        <select name="mentor_id" id="mentor_id" required>
            <option value="">-- Choose Mentor --</option>
            <?php foreach ($mentors as $id): ?>
                <option value="<?= $id ?>">Mentor ID: <?= $id ?></option>
            <?php endforeach; ?>
        </select>

        <label for="type">Resource Type:</label>
        <select name="type" id="type" required>
            <option value="">-- Select Type --</option>
            <option value="E-Book">E-Book</option>
            <option value="Slides">Slides</option>
            <option value="Video Lecture">Video Lecture</option>
            <option value="Notes">Notes</option>
        </select>

        <label for="message">Message / Description:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <button type="submit">Send Request</button>
    </form>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
