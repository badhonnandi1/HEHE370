<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'database.php';

$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$success = "";
$error = "";

$mentor_query = $conn->prepare("SELECT mentor_id FROM Student WHERE ID = ?");
$mentor_query->bind_param("i", $student_id);
$mentor_query->execute();
$mentor_query->bind_result($mentor_id);
$mentor_query->fetch();
$mentor_query->close();

if (!$mentor_id) {
    $error = "No mentor assigned. You cannot send a resource request.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $type = $_POST['type'];
    $message = trim($_POST['message']);
    $status = "pending";
    $date = date("Y-m-d");

    if (!empty($type) && !empty($message)) {
        $insert = $conn->prepare("INSERT INTO Request (request_at, status, type, message, s_id, m_id) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssii", $date, $status, $type, $message, $student_id, $mentor_id);
        if ($insert->execute()) {
            $success = "Request submitted successfully!";
        } else {
            $error = "Database Error: " . $conn->error;
        }
        $insert->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Request Resource</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="request_res.css">
</head>
<body>

<header>
  <div class="container">
    <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="announcement_show.php">Announcements</a></li>
        <li><a href="mentor_list_all.php">My Mentor</a></li>
        <li><a href="advisors.php">My Advisor</a></li>
        <li><a href="group_chat.php">Lets Chat</a></li>
        <li><a href="grade_sheet.php">Grade Sheet</a></li>

        <li><a href="view_resource.php">Resources</a></li>

        <li><a href="request_resource.php">Request Resources</a></li>


        <li><a href="show_profile.php">My Profile</a></li>
        <?php if (!$role): ?>
          <li><a href="login.php">Login</a></li>
        <?php else: ?>
          <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>

<div class="form-box">
    <h2>Request a Resource</h2>
    <?php if ($success): ?>
        <p class="message"><?= htmlspecialchars($success) ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($mentor_id): ?>
        <form method="POST">
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
    <?php else: ?>
        <p style="color:red;">You don't have an assigned mentor yet. Contact the admin.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
