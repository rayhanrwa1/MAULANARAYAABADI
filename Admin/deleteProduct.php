<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate and get the 'delete' parameter
if (isset($_GET['delete']) && !empty($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = $_GET['delete'];
} else {
    header("Location: productManagement.php?error=1&msg=InvalidRequest");
    exit();
}

// Validate and get the 'photo_type' and 'photo_filename' parameters if they exist
$photo_type = isset($_GET['photo_type']) && !empty($_GET['photo_type']) ? $_GET['photo_type'] : null;
$photo_filename = isset($_GET['photo_filename']) && !empty($_GET['photo_filename']) ? $_GET['photo_filename'] : null;

// Perform the deletion in the database
$sql = "DELETE FROM tbl_pdk_893kk WHERE product_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    // If deletion was successful, handle photo deletion if necessary
    if (!is_null($photo_filename) && !empty($photo_filename)) {
        // If a photo file is provided, attempt to delete it from the server
        $file_path = "../admin/assets/image_db/berita/" . $photo_filename;
        if (file_exists($file_path)) {
            if (!unlink($file_path)) {
                // Handle file deletion failure
                header("Location: productManagement.php?success=1&deleted_id=" . $product_id . "&msg=FileDeleteFailed");
                exit();
            }
        }
    }
    
    // Redirect with success flag
    header("Location: productManagement.php?success=1&deleted_id=" . $product_id);
    exit();
} else {
    // Handle error if deletion failed
    header("Location: productManagement.php?error=1&msg=DeleteFailed");
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
