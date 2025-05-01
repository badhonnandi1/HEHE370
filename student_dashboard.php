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
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
  <div class="container">
    <h1>Student Dashboard</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">My Advisor</a></li>
        <li><a href="#">Student List</a></li>
        <li><a href="#">Lets Chat</a></li>
        <li><a href="#">Resources</a></li>
        <li><a href="#">Announcements</a></li>
      </ul>
    </nav>
  </div>
</header>

<section class="dashboard">
  <div class="container">
    <h2>Welcome, Student!</h2>
    <div class="cards">
      <div class="card">
        <h3>Advisor Info</h3>
        <p>Find and contact your assigned advisor.</p>
      </div>
      <div class="card">
        <h3>Mentor Support</h3>
        <p>Connect with your mentors for guidance.</p>
      </div>
      <div class="card">
        <h3>Resources</h3>
        <p>Access study materials and e-books.</p>
      </div>
      <div class="card">
        <h3>Announcements</h3>
        <p>Stay updated with latest university news.</p>
      </div>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    <p>&copy; 2025 University Portal. Student Access.</p>
  </div>
</footer>

</body>
</html>
