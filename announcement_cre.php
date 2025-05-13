<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;include 'database.php';


$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = $_POST['name'];
    $madeby      = $_SESSION['user_id'];
    $title       = $_POST['title'];
    $content     = $_POST['content'];
    $date        = date("Y-m-d");
    $target_role = $_POST['target_role'];
    // $adv_id      = $_SESSION['user_id']; // apatoto lagtese na -> view er somoy dekhte hobe eita ki kora jay

    echo $adv_id;

    $insert = mysqli_query($conn, "INSERT INTO Announcement (madeby, title, Content, date, target_role,name) VALUES ('$madeby', '$title', '$content', '$date', '$target_role','$name')");

    if ($insert) {
        $success = "Announcement Given Successfully!";
    } else {
        $error = "Failed to Create Announcement: " . mysqli_error($conn);
    }
}
?>


<!-- INSERT INTO Announcement (madeby, title, Content, date, target_role, adv_id,name) VALUES (3470, 'Hello World', 'Hopeful', '2024-01-01', 'student', 3470,'Badhonnandi') -->

<!DOCTYPE html>
<html>
<head>
    <title>Create Announcement</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="CSS/announcement_cre.css">

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
<div class="form-box">
    <h2>Create New Announcement</h2>
    <?php if ($success): ?>
        <p class="message"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
   
        <label for="title">Announcement Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="content">Content:</label>
        <textarea name="content" id="content" required></textarea>

        <label for="target_role">Target Role:</label>
        <select name="target_role" id="target_role" required>
            <option value="">--Select Role--</option>
            <option value="Student">Student</option>
            <option value="Mentor">Mentor</option>
            <option value="Both">Both</option>

        </select>

        <button type="submit">Create Announcement</button>
    </form>
</div>
<footer>
    <div class="container">
      <p>&copy; 2025 OOA STUDENT PORTAL. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>