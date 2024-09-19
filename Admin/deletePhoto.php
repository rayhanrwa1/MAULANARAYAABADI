<?php
require 'connection.php'; // Pastikan ini mengimpor pengaturan koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_photo'])) {
    $photo_type = $_POST['photo_type'];
    $photo_filename = $_POST['photo_filename'];
    $product_id = $_POST['product_id'];

    // Hapus foto dari database
    $sql = "UPDATE tbl_pdk_893kk SET $photo_type = NULL WHERE product_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    if ($stmt->execute()) {
        // Hapus file dari server
        $photo_path = "./assets/image_db/produk/" . (strpos($photo_type, 'product_photo_update_2') !== false ? 'produk2/' : (strpos($photo_type, 'product_photo_update_3') !== false ? 'produk3/' : 'produk4/')) . $photo_filename;
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => htmlspecialchars($stmt->error)]);
    }
    $stmt->close();
    $conn->close();
}
?>
