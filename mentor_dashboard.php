<?php
session_start();
include 'database.php';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mentor Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
  <div class="container">
    <h1>Mentor Dashboard</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="m_my_students.php">My Students</a></li>
        <li><a href="group_chat.php">Lets Chat</a></li>
        <li><a href="manage_resources.php">Manage Requests</a></li>
        <li><a href="upload_resource.php">Upload Requests</a></li>

        <li><a href="announcement_cre.php">Give Announcements</a></li>
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

<section class="dashboard">
  <div class="container">
    <h2>Welcome, Mentor!</h2>
    <div class="cards">
      <div class="card">
        <h3>Student List</h3>
        <p>View and guide your assigned students.</p>
      </div>
      <div class="card">
        <h3>Learning Resources</h3>
        <p>Share study materials with your mentees.</p>
      </div>
      <div class="card">
        <h3>Check Announcements</h3>
        <p>Stay updated with university notices.</p>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <p>&copy; 2025 University Portal. Mentor Access.</p>
  </div>
</footer>

</body>
</html>
