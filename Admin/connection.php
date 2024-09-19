<?php
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "emp_r3dblzq4m";
$servername = "localhost";
$username = "u1589942_ptmra_database_set"; 
$password = "Admin1212#"; 
$dbname = "u1589942_ptmra_database_set"; 


// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
