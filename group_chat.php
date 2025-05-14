<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = strtolower($_SESSION['role']);
$mentor_id = null;
$sender_name = "";

// Get sender name from USER table
$name_stmt = $conn->prepare("SELECT Name FROM USER WHERE ID = ?");
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_stmt->bind_result($sender_name);
$name_stmt->fetch();
$name_stmt->close();

// Determine mentor_id
if ($role === 'student') {
    $stmt = $conn->prepare("SELECT mentor_id FROM Student WHERE ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($mentor_id);
    $stmt->fetch();
    $stmt->close();

    if (!$mentor_id) {
        die("No mentor assigned. Contact the administrator.");
    }
} elseif ($role === 'mentor') {
    $mentor_id = $user_id;
} else {
    die("Invalid role.");
}

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $msg = trim($_POST['message']);
    if (!empty($msg)) {
        $stmt = $conn->prepare("INSERT INTO Chat (chat_type, message, s_id, m_id) VALUES ('group', ?, ?, ?)");
        if ($role === 'student') {
            $stmt->bind_param("sii", $msg, $user_id, $mentor_id);
        } else {
            $null = NULL;
            $stmt->bind_param("sii", $msg, $null, $user_id);
        }
        $stmt->execute();
        $stmt->close();
        header("Location: group_chat.php");
        exit();
    }
}

$query = "
    SELECT c.message, c.time_sent, u.ID AS sender_id, u.Name AS sender_name,
           CASE 
               WHEN c.s_id IS NOT NULL THEN 'Student' 
               WHEN c.m_id IS NOT NULL THEN 'Mentor'
               ELSE 'Unknown'
           END AS sender_role
    FROM Chat c
    LEFT JOIN USER u ON u.ID = COALESCE(c.s_id, c.m_id)
    WHERE c.m_id = ? OR c.s_id IN (SELECT ID FROM Student WHERE mentor_id = ?)
    ORDER BY c.time_sent ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $mentor_id, $mentor_id);
$stmt->execute();
$messages = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Group Chat</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="group_chat.css">
    
</head>
<body>

<header>
  <div class="container">
  <h1>OFFICE OF ACADEMIC ACTIVITIES</h1>
  <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="advisors.php">My Advisor</a></li>
        <li><a href="group_chat.php">Lets Chat</a></li>
        <li><a href="grade_sheet.php">Grade Sheet</a></li>

        <li><a href="view_resource.php">Resources</a></li>

        <li><a href="request_resource.php">Request Resources</a></li>


        <li><a href="announcement_show.php">Announcements</a></li>
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
    <div class="chat-box">
        <?php while ($row = $messages->fetch_assoc()): ?>
            <?php $isOwn = $row['sender_id'] == $user_id; ?>
            <div class="chat-msg <?= $isOwn ? 'own' : 'other' ?>">
                <span class="sender"><?= htmlspecialchars($row['sender_name']) ?> </span><br>
                <?= nl2br(htmlspecialchars($row['message'])) ?><br>
                <span class="time"><?= $row['time_sent'] ?></span>
            </div>
        <?php endwhile; ?>
    </div>

    <form method="POST">
        <textarea name="message" rows="3" placeholder="Type your message..." required></textarea><br>
        <button type="submit">Send</button>
    </form>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
