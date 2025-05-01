<?php
$servername = "localhost";  
$name = "root";         
$password = "";            
$dbname = "OAA_STUDENT_MANAGEMENT_SYSTEM"; 

$conn = new mysqli($servername, $name, $password, 'OAA_STUDENT_MANAGEMENT_SYSTEM');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";

?>




