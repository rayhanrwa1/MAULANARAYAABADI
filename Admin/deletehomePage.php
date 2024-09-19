<?php
// Mulai session
session_start();

// Hubungkan dengan database
include 'connection.php';

// Periksa apakah parameter ID ada di URL
if (isset($_GET['id'])) {
    // Ambil ID dari URL
    $id = intval($_GET['id']);

    // Query untuk menghapus data berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM poster_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, buat pesan sukses
        $_SESSION['message'] = "Data berhasil dihapus!";
        $_SESSION['msg_type'] = "success";
    } else {
        // Jika penghapusan gagal, buat pesan kesalahan
        $_SESSION['message'] = "Gagal menghapus data.";
        $_SESSION['msg_type'] = "danger";
    }

    // Tutup statement
    $stmt->close();

    // Alihkan kembali ke halaman utama (misalnya index.php)
    header("Location: homeUser.php");
    exit();
} else {
    // Jika tidak ada ID di URL, alihkan kembali ke halaman utama
    header("Location: homeUser.php");
    exit();
}
?>
