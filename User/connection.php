<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "emp_r3dblzq4m";
$servername = "localhost";
$username = "u1589942_ptmra_database_set"; 
$password = "Admin1212#"; 
$dbname = "u1589942_ptmra_database_set"; 


// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
