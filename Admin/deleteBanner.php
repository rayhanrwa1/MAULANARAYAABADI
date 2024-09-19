<?php
include 'connection.php';

// Pastikan request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        // Ambil nama file gambar dari database
        $stmt = $conn->prepare("SELECT product_1, product_2, product_3 FROM banner_tbl_index WHERE id = ?");
        $stmt->bind_param('i', $id);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            if ($row) {
                $uploadDirectory = 'assets/image_db/banner/';
                
                // Hapus file gambar jika ada dan file benar-benar ada di direktori
                if ($row['product_1'] && file_exists($uploadDirectory . $row['product_1'])) {
                    if (!unlink($uploadDirectory . $row['product_1'])) {
                        echo json_encode(['success' => false, 'error' => 'Failed to delete product_1']);
                        exit();
                    }
                }
                if ($row['product_2'] && file_exists($uploadDirectory . $row['product_2'])) {
                    if (!unlink($uploadDirectory . $row['product_2'])) {
                        echo json_encode(['success' => false, 'error' => 'Failed to delete product_2']);
                        exit();
                    }
                }
                if ($row['product_3'] && file_exists($uploadDirectory . $row['product_3'])) {
                    if (!unlink($uploadDirectory . $row['product_3'])) {
                        echo json_encode(['success' => false, 'error' => 'Failed to delete product_3']);
                        exit();
                    }
                }
                
                // Hapus data dari database
                $stmt = $conn->prepare("DELETE FROM banner_tbl_index WHERE id = ?");
                $stmt->bind_param('i', $id);
                
                if ($stmt->execute()) {
                    // Kirim respons sukses ke JavaScript
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => $conn->error]);
                }
                $stmt->close();
            } else {
                echo json_encode(['success' => false, 'error' => 'Data not found']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute SELECT query']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
}
?>
