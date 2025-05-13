<?php
session_start();
include 'database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//if (!isset($_SESSION['user_id'])) {
    //$_SESSION['user_id'] = 2001; // Replace with actual mentor ID
//}

$mentor_id = $_SESSION['user_id'];

$query = "
    SELECT R.r_id, R.request_at, R.status, R.type, R.message, R.s_id, U.Name AS student_name
    FROM Request R
    JOIN Student S ON R.s_id = S.ID
    JOIN User U ON S.ID = U.ID
    WHERE R.m_id = $mentor_id
    ORDER BY R.request_at DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Resource Requests</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="view_requests.css">
</head>
<body>

<header>
    <div class="container">
        <h1>Mentor Dashboard - Resource Requests</h1>
        <nav>
            <ul>
                <li><a href="mentor_dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <h2>Resource Requests Sent to You</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['request_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No resource requests found.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; 2025 OAA Management System</p>
    </div>
</footer>

</body>
</html>
