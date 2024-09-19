<?php
// Cek apakah sesi sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php'; // Include the database connection

// Inisialisasi variabel $is_admin dengan nilai default
$is_admin = 0;

if (isset($_SESSION['username_employee'])) {
    $username = $_SESSION['username_employee'];
    
    // Cek apakah koneksi database tersedia
    if ($conn) {
        // Persiapkan statement SQL
        $stmt = $conn->prepare("SELECT is_admin FROM tbl_emp_7tt8 WHERE username_employee = ?");
        
        if ($stmt) {
            // Bind parameter
            $stmt->bind_param('s', $username);
            $stmt->execute();
            
            // Bind hasil ke variabel
            $stmt->bind_result($is_admin_result);
            
            // Fetch hasil
            if ($stmt->fetch()) {
                $is_admin = (int) $is_admin_result; // Ambil nilai is_admin dari database dan pastikan tipe data integer
            }
            
            // Tutup statement
            $stmt->close();
        } else {
            // Tangani kesalahan jika statement gagal dipersiapkan
            error_log("Statement preparation failed: " . $conn->error);
        }
    } else {
        // Tangani kesalahan jika koneksi database gagal
        error_log("Database connection failed: " . $conn->connect_error);
    }
}
?>
