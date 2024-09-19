
<?php
$title = 'index'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username_employee'])) {
    header('Location: signin.php');
    exit();
}

include './partials/head.php';
include 'connection.php';

// Retrieve employee info
$username = $_SESSION['username_employee'];
$stmt = $conn->prepare("SELECT employee_name, position_name, photo FROM tbl_emp_7tt8 WHERE username_employee = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($employee_name, $position_name, $photo);
$stmt->fetch();
$stmt->close();

$employee_name = empty($employee_name) ? "Unknown User" : htmlspecialchars($employee_name);
$position_name = empty($position_name) ? "Unknown Position" : htmlspecialchars($position_name);
$photo = empty($photo) ? "assets/img/avatar/user_profile.svg" : htmlspecialchars($photo);

// Initialize variables for product editing
$product_id = '';
$item_name = '';
$product_number = '';
$price = '';
$promo_price = '';
$category = '';
$item_description = '';
$product_type = '';
$whatsapp_link = '';
$shopee_link = '';
$tokopedia_link = '';
$product_photo_update = '';
$brochure_update = '';
$product_photo_update_2 = ''; // New for Product Photo 2
$product_photo_update_3 = ''; // New for Product Photo 3
$product_photo_update_4 = ''; // New for Product Photo 4
$stok_status = '';
$is_popular = 0;

if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $sql = "SELECT * FROM tbl_pdk_893kk WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $item_name = $product['item_name'];
        $product_number = $product['product_number'];
        $price = $product['price'];
        $promo_price = $product['promo_price'];
        $category = $product['category'];
        $item_description = $product['item_description'];
        $product_type = $product['product_type'];
        $stok_status = $product['stok_status'];
        $whatsapp_link = $product['whatsapp_link'];
        $shopee_link = $product['shopee_link'];
        $tokopedia_link = $product['tokopedia_link'];
        $product_photo_update = $product['product_photo_update'];
        $product_photo_update_2 = $product['product_photo_update_2']; // New
        $product_photo_update_3 = $product['product_photo_update_3']; // New
        $product_photo_update_4 = $product['product_photo_update_4']; // New
        $brochure_update = $product['brochure_update'];
        $is_popular = $product['is_popular']; // New
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $product_number = $_POST['product_number'];
    $price = $_POST['price'];
    $promo_price = $_POST['promo_price'];
    $category = $_POST['category'];
    $item_description = $_POST['item_description'];
    $product_type = $_POST['product_type'];
    $whatsapp_link = $_POST['whatsapp_link'];
    $shopee_link = $_POST['shopee_link'];
    $tokopedia_link = $_POST['tokopedia_link'];
    $stok_status = ($_POST['stok_status'] == 'ready') ? 1 : 0;
    $is_popular = isset($_POST['is_popular']) ? (int)$_POST['is_popular'] : 0; // Pastikan is_popular adalah integer

    // Handle file uploads
    $new_photo_uploaded = false;
    $new_photo_uploaded_2 = false;
    $new_photo_uploaded_3 = false;
    $new_photo_uploaded_4 = false;
    $new_brochure_uploaded = false;

    if (isset($_FILES['product_photo_update']) && $_FILES['product_photo_update']['error'] == UPLOAD_ERR_OK) {
        $product_photo_update = $_FILES['product_photo_update']['name'];
        $photo_tmp_name = $_FILES['product_photo_update']['tmp_name'];
        $photo_target = "./assets/image_db/produk/" . basename($product_photo_update);
        $new_photo_uploaded = move_uploaded_file($photo_tmp_name, $photo_target);
    }

    if (isset($_FILES['product_photo_update_2']) && $_FILES['product_photo_update_2']['error'] == UPLOAD_ERR_OK) {
        $product_photo_update_2 = $_FILES['product_photo_update_2']['name'];
        $photo_tmp_name_2 = $_FILES['product_photo_update_2']['tmp_name'];
        $photo_target_2 = "./assets/image_db/produk/produk2/" . basename($product_photo_update_2);
        $new_photo_uploaded_2 = move_uploaded_file($photo_tmp_name_2, $photo_target_2);
    }
    
    if (isset($_FILES['product_photo_update_3']) && $_FILES['product_photo_update_3']['error'] == UPLOAD_ERR_OK) {
        $product_photo_update_3 = $_FILES['product_photo_update_3']['name'];
        $photo_tmp_name_3 = $_FILES['product_photo_update_3']['tmp_name'];
        $photo_target_3 = "./assets/image_db/produk/produk3/" . basename($product_photo_update_3);
        $new_photo_uploaded_3 = move_uploaded_file($photo_tmp_name_3, $photo_target_3);
    }
    
    if (isset($_FILES['product_photo_update_4']) && $_FILES['product_photo_update_4']['error'] == UPLOAD_ERR_OK) {
        $product_photo_update_4 = $_FILES['product_photo_update_4']['name'];
        $photo_tmp_name_4 = $_FILES['product_photo_update_4']['tmp_name'];
        $photo_target_4 = "./assets/image_db/produk/produk4/" . basename($product_photo_update_4);
        $new_photo_uploaded_4 = move_uploaded_file($photo_tmp_name_4, $photo_target_4);
    }

    if (isset($_FILES['brochure_update']) && $_FILES['brochure_update']['error'] == UPLOAD_ERR_OK) {
        $brochure_update = $_FILES['brochure_update']['name'];
        $brochure_tmp_name = $_FILES['brochure_update']['tmp_name'];
        $brochure_target = "./assets/image_db/produkFile/" . basename($brochure_update);
        $new_brochure_uploaded = move_uploaded_file($brochure_tmp_name, $brochure_target);
    }

    // If no new photos are uploaded, keep the old ones
    if (!$new_photo_uploaded && isset($product_id)) {
        $sql = "SELECT product_photo_update FROM tbl_pdk_893kk WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product_photo_update = $result->fetch_assoc()['product_photo_update'];
        }
        $stmt->close();
    }

    if (!$new_photo_uploaded_2 && isset($product_id)) {
        $sql = "SELECT product_photo_update_2 FROM tbl_pdk_893kk WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product_photo_update_2 = $result->fetch_assoc()['product_photo_update_2'];
        }
        $stmt->close();
    }

    if (!$new_photo_uploaded_3 && isset($product_id)) {
        $sql = "SELECT product_photo_update_3 FROM tbl_pdk_893kk WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product_photo_update_3 = $result->fetch_assoc()['product_photo_update_3'];
        }
        $stmt->close();
    }

    if (!$new_photo_uploaded_4 && isset($product_id)) {
        $sql = "SELECT product_photo_update_4 FROM tbl_pdk_893kk WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $product_photo_update_4 = $result->fetch_assoc()['product_photo_update_4'];
        }
        $stmt->close();
    }
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        
               // Update SQL query
               $sql = "UPDATE tbl_pdk_893kk SET 
               item_name=?, 
               product_number=?, 
               price=?, 
               promo_price=?, 
               category=?, 
               item_description=?, 
               product_type=?, 
               stok_status=?, 
               whatsapp_link=?, 
               shopee_link=?, 
               tokopedia_link=?, 
               product_photo_update=?, 
               product_photo_update_2=?, 
               product_photo_update_3=?, 
               product_photo_update_4=?, 
               brochure_update=?, 
               is_popular=? 
               WHERE product_id=?";
           $stmt = $conn->prepare($sql);
           if ($stmt === false) {
               die('Prepare failed: ' . htmlspecialchars($conn->error));
           }
           $stmt->bind_param("sssssssissssssssii", 
           $item_name, 
           $product_number, 
           $price, 
           $promo_price, 
           $category, 
           $item_description, 
           $product_type, 
           $stok_status, 
           $whatsapp_link, 
           $shopee_link, 
           $tokopedia_link, 
           $product_photo_update, 
           $product_photo_update_2, 
           $product_photo_update_3, 
           $product_photo_update_4, 
           $brochure_update, 
           $is_popular, 
           $product_id  // Integer, jadi 'i'
       );    
       } else {
           // Insert SQL query
           $sql = "INSERT INTO tbl_pdk_893kk (
               item_name, 
               product_number, 
               price, 
               promo_price, 
               category, 
               item_description, 
               product_type, 
               stok_status, 
               whatsapp_link, 
               shopee_link, 
               tokopedia_link, 
               product_photo_update, 
               product_photo_update_2, 
               product_photo_update_3, 
               product_photo_update_4, 
               brochure_update, 
               is_popular) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
           $stmt = $conn->prepare($sql);
           if ($stmt === false) {
               die('Prepare failed: ' . htmlspecialchars($conn->error));
           }
           $stmt->bind_param("sssssssissssssssi", 
               $item_name, 
               $product_number, 
               $price, 
               $promo_price, 
               $category, 
               $item_description, 
               $product_type, 
               $stok_status,  // Integer
               $whatsapp_link, 
               $shopee_link, 
               $tokopedia_link, 
               $product_photo_update, 
               $product_photo_update_2, 
               $product_photo_update_3, 
               $product_photo_update_4, 
               $brochure_update, 
               $is_popular  // Integer
           );
       }
       
        
    // Execute the query and handle success or failure
    if ($stmt->execute()) {
        $success = true;
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Data Successfully Saved',
                showConfirmButton: true
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Data Failed to Save',
                text: '" . htmlspecialchars($stmt->error) . "',
                showConfirmButton: true
            });
        </script>";
    }
    $stmt->close();
}

// Fetch product data
$sql = "SELECT * FROM tbl_pdk_893kk";
$products_result = $conn->query($sql);

// SQL query to fetch notifications
$notificationsQuery = "
    SELECT a.employee_name, a.emp_number, a.photo, b.forgot_password_request_at
    FROM tbl_access_guard55 AS b
    JOIN tbl_emp_7tt8 AS a ON a.emp_number = b.emp_number
    WHERE b.forgot_password_request_at IS NOT NULL
    ORDER BY b.forgot_password_request_at DESC
    LIMIT 5";

// Execute the query
$notificationsResult = $conn->query($notificationsQuery);
$notifications = ($notificationsResult === FALSE) ? [] : $notificationsResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM tbl_pdk_893kk WHERE product_id=?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param('i', $product_id);
    if ($stmt->execute()) {
        // Redirect after delete to avoid resubmission
        header('Location: recruitmentView.php');
        exit();
    } else {
        echo "Error deleting record: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

?>