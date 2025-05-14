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
      <h1>OFFICE OF ACADEMIC ACTIVITIES
      </h1>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="student_list_all.php">All Student List</a></li>
          <li><a href="mentor_list_all.php ">Mentors</a></li>
          <li><a href="add_drop.php">Add or Drop</a></li>
          <li><a href="announcement_show.php">Announcements</a></li>

          <li><a href="announcement_cre.php">Create Announcements</a></li>
          <li><a href="assign_m_s.php">Assign Student</a></li>

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

  <section class="dashboard">
    <div class="container">
      <h2>Welcome, Mr Advisor!</h2>
      <div class="cards">
        <div class="card">
          <h3>Post Announcements</h3>
          <p>Create and publish announcements.</p>
        </div>
        <div class="card">
          <h3>Student List</h3>
          <p>View and manage all freshers students.</p>
        </div>
        <div class="card">
          <h3>Mentor List</h3>
          <p>Access the full list of student mentors.</p>
        </div>
        <div class="card">
          <h3>Assign Students</h3>
          <p>Assign students to mentors or faculty advisors.</p>
        </div>

      </div>
    </div>
  </section>


  <footer>
    <div class="container">
      <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
    </div>
  </footer>

</body>

</html>