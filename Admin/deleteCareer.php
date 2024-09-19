<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dari database berdasarkan ID
    $query = "DELETE FROM tbl_karier WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect ke halaman recruitmentView.php
    if (isset($_GET['redirect'])) {
        header('Location: ' . $_GET['redirect']);
    } else {
        header('Location: recruitmentView.php');
    }
    exit();
}
?>
