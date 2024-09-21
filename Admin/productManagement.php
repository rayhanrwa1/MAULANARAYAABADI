<!doctype html>
<html lang="en" dir="ltr">

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

$employee_name = htmlspecialchars($employee_name ?: "Unknown User");
$position_name = htmlspecialchars($position_name ?: "Unknown Position");
$photo = htmlspecialchars($photo ?: "assets/img/avatar/user_profile.svg");

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

// Initialize upload variables
$new_files_uploaded = [
    'product_photo_update' => false,
    'product_photo_update_2' => false,
    'product_photo_update_3' => false,
    'product_photo_update_4' => false,
    'brochure_update' => false
];

if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];
    $sql = "SELECT * FROM tbl_pdk_893kk WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Assign values
        $item_name = $product['item_name'];
        $product_number = $product['product_number'];
        $price = $product['price'];
        $promo_price = $product['promo_price'];
        $category = $product['category'];
        $item_description = $product['item_description'];
        $product_type = $product['product_type'];
        $stok_status = $product['stok_status'];
        $is_popular = $product['is_popular'];
        $whatsapp_link = $product['whatsapp_link'];
        $shopee_link = $product['shopee_link'];
        $tokopedia_link = $product['tokopedia_link'];
        $product_photo_update = $product['product_photo_update'];
        $product_photo_update_2 = $product['product_photo_update_2'];
        $product_photo_update_3 = $product['product_photo_update_3'];
        $product_photo_update_4 = $product['product_photo_update_4'];
        $brochure_update = $product['brochure_update'];
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission and file uploads
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
    $is_popular = ($_POST['is_popular'] == '1') ? 1 : 0;

    // Handle file uploads
    $upload_paths = [
        'product_photo_update' => "./assets/image_db/produk/",
        'product_photo_update_2' => "./assets/image_db/produk/produk2/",
        'product_photo_update_3' => "./assets/image_db/produk/produk3/",
        'product_photo_update_4' => "./assets/image_db/produk/produk4/",
        'brochure_update' => "./assets/image_db/produkFile/"
    ];

    $old_files = [];
    if ($product_id) {
        // Fetch existing file paths from database
        $sql = "SELECT product_photo_update, product_photo_update_2, product_photo_update_3, product_photo_update_4, brochure_update FROM tbl_pdk_893kk WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $files = $result->fetch_assoc();
            $old_files = [
                'product_photo_update' => $files['product_photo_update'],
                'product_photo_update_2' => $files['product_photo_update_2'],
                'product_photo_update_3' => $files['product_photo_update_3'],
                'product_photo_update_4' => $files['product_photo_update_4'],
                'brochure_update' => $files['brochure_update']
            ];
        }
        $stmt->close();
    }

    // Process file uploads
    foreach ($upload_paths as $key => $path) {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] === UPLOAD_ERR_OK) {
            // Remove old file if exists
            if (isset($old_files[$key]) && !empty($old_files[$key])) {
                $old_file_path = $path . basename($old_files[$key]);
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
            
            $file_name = $_FILES[$key]['name'];
            $tmp_name = $_FILES[$key]['tmp_name'];
            $target = $path . basename($file_name);
            $new_files_uploaded[$key] = move_uploaded_file($tmp_name, $target);
            $$key = $file_name;
        } else {
            // If no new file uploaded, use old file
            if (!isset($new_files_uploaded[$key]) && isset($product_id)) {
                $$key = $old_files[$key] ?? ''; // Default to empty string if not set
            }
        }
    }

    if ($product_id) {
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
            $stok_status,  // Boolean, jadi 'i'
            $whatsapp_link, 
            $shopee_link, 
            $tokopedia_link, 
            $product_photo_update, 
            $product_photo_update_2, 
            $product_photo_update_3, 
            $product_photo_update_4, 
            $brochure_update, 
            $is_popular  // Boolean, jadi 'i'
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
$notifications = [];

if ($notificationsResult !== FALSE) {
    while ($row = $notificationsResult->fetch_assoc()) {
        $notifications[] = $row;
    }
}


// Handle product delete
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Product ID dari URL atau form
    $product_id = $_GET['edit'];

    // Tentukan folder tempat gambar disimpan
    $delete_paths = [
        'product_photo_update_2' => "./assets/image_db/produk/produk2/",
        'product_photo_update_3' => "./assets/image_db/produk/produk3/",
        'product_photo_update_4' => "./assets/image_db/produk/produk4/"
    ];

    $success_delete = false; // Variabel untuk mengecek jika ada yang berhasil dihapus

    // Cek apakah checkbox penghapusan dicentang
    foreach ($delete_paths as $key => $path) {
        if (isset($_POST['delete_' . $key]) && $_POST['delete_' . $key] == 1) {
            // Ambil nama file dari database
            $sql = "SELECT $key FROM tbl_pdk_893kk WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $product_id);
            $stmt->execute();
            $stmt->bind_result($file_name);
            $stmt->fetch();
            $stmt->close();

            if ($file_name) {
                $file_path = $path . $file_name;
                if (file_exists($file_path)) {
                    // Hapus file dari server
                    if (unlink($file_path)) {
                        $success_delete = true; // Tandai sukses jika file berhasil dihapus
                    }
                }

                // Kosongkan referensi file di database
                $sql = "UPDATE tbl_pdk_893kk SET $key='' WHERE product_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $product_id);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Cek apakah ada file yang berhasil dihapus
    if ($success_delete) {
        // SweetAlert untuk notifikasi sukses
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Gambar berhasil dihapus!',
                confirmButtonText: 'OK'
            }).then(function() {
                window.location.href = 'halaman_sekarang.php'; // Ubah ini ke halaman yang sesuai
            });
        </script>";
    } else {
        // Jika tidak ada file yang dihapus
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Tidak ada gambar yang dihapus!',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}


?>


<body class="geex-dashboard">
    
<?php include './partials/header.php'?>

<main class="geex-main-content">
        
<?php include './partials/sidebar.php'?>
    
<?php include './partials/customizer.php'?>

<div class="geex-content">
        <div class="geex-content__header">
            <div class="geex-content__header__content">
                <h2 class="geex-content__header__title">Manajemen Produk</h2>
                <p class="geex-content__header__subtitle">Halaman Manajemen Produk PT Maulana Raya Abadi</p>
            </div>

            <div class="geex-content__header__action">
                <div class="geex-content__header__customizer">
                    <button class="geex-btn geex-btn__toggle-sidebar">   
                        <i class="uil uil-align-center-alt"></i> 
                    </button>
                </div> 
                <div class="geex-content__header__action__wrap">
                    <ul class="geex-content__header__quickaction">
                        <li class="geex-content__header__quickaction__item">
                            <a href="#" class="geex-content__header__quickaction__link">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 20H10C10 20.5304 10.2107 21.0391 10.5858 21.4142C10.9609 21.7893 11.4696 22 12 22C12.5304 22 13.0391 21.7893 13.4142 21.4142C13.7893 21.0391 14 20.5304 14 20H20C20.2652 20 20.5196 19.8946 20.7071 19.7071C20.8946 19.5196 21 19.2652 21 19C21 18.7348 20.8946 18.4804 20.7071 18.2929C20.5196 18.1054 20.2652 18 20 18V11C20 8.87827 19.1571 6.84344 17.6569 5.34315C16.1566 3.84285 14.1217 3 12 3C9.87827 3 7.84344 3.84285 6.34315 5.34315C4.84285 6.84344 4 8.87827 4 11V18C3.73478 18 3.48043 18.1054 3.29289 18.2929C3.10536 18.4804 3 18.7348 3 19C3 19.2652 3.10536 19.5196 3.29289 19.7071C3.48043 19.8946 3.73478 20 4 20V20ZM6 11C6 9.4087 6.63214 7.88258 7.75736 6.75736C8.88258 5.63214 10.4087 5 12 5C13.5913 5 15.1174 5.63214 16.2426 6.75736C17.3679 7.88258 18 9.4087 18 11V18H6V11Z" fill="#464255"/>
                                </svg>                                            
                                <span class="geex-content__header__badge bg-info"><?php echo $notificationsResult->num_rows; ?></span>
                            </a>
                            <div class="geex-content__header__popup geex-content__header__popup--notification">
                                <h3 class="geex-content__header__popup__title">
                                    Notification<span class="content__header__popup__title__count"><?php echo count($notifications); ?></span>
                                </h3>
                                <div class="geex-content__header__popup__content">
                                    <ul class="geex-content__header__popup__items">
                                        <?php if (empty($notifications)): ?>
                                            <li class="geex-content__header__popup__item">
                                                <div class="geex-content__header__popup__item__content">
                                                <p>Tidak ada permintaan pemulihan kata sandi yang ditemukan.</p>
                                                </div>
                                            </li>
                                        <?php else: ?>
                                            <?php foreach ($notifications as $notification): ?>
                                                <li class="geex-content__header__popup__item">
                                                    <a class="geex-content__header__popup__link" href="#">
                                                        <div class="geex-content__header__popup__item__img">
                                                            <img src="<?php echo htmlspecialchars($notification['photo']); ?>" alt="Popup Img" class="" />
                                                        </div>
                                                        <div class="geex-content__header__popup__item__content">
                                                            <h5 class="geex-content__header__popup__item__title">
                                                                <?php echo htmlspecialchars($notification['employee_name']); ?>
                                                                <span class="geex-content__header__popup__item__time">
                                                                    <?php echo htmlspecialchars($notification['forgot_password_request_at']); ?>
                                                                </span>
                                                            </h5>
                                                            <div class="geex-content__header__popup__item__desc">
                                                                Permintaan reset kata sandi oleh pengguna dengan nomor pegawai: <?php echo htmlspecialchars($notification['emp_number']); ?>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="geex-content__header__quickaction__item">
                            <a href="#" class="geex-content__header__quickaction__link">
                                <img class="user-img" src="<?php echo $photo; ?>" alt="user" style="border-radius: 10px;" />
                            </a>
                            <div class="geex-content__header__popup geex-content__header__popup--author">
                                <div class="geex-content__header__popup__header">
                                    <div class="geex-content__header__popup__header__img">
                                        <img class="user-img" src="<?php echo $photo; ?>" alt="user" style="border-radius: 10px;" />
                                    </div>
                                    <div class="geex-content__header__popup__header__content">
                                        <h3 class="geex-content__header__popup__header__title"><?php echo $employee_name; ?></h3>
                                        <span class="geex-content__header__popup__header__subtitle"><?php echo $position_name; ?></span>
                                    </div>
                                </div>
                                <div class="geex-content__header__popup__content">
                                    <ul class="geex-content__header__popup__items">
                                        <li class="geex-content__header__popup__item">
                                            <a class="geex-content__header__popup__link" href="profile.php">
                                                <i class="uil uil-user"></i>
                                                Profile
                                            </a>
                                        </li>
                                        <li class="geex-content__header__popup__item">
                                            <a class="geex-content__header__popup__link" href="change_passwd_prof.php">
                                            <i class="uil uil-lock"></i>
                                                Atur Ulang Kata Sandi
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="geex-content__header__popup__footer">
                                    <a href="logout.php" class="geex-content__header__popup__footer__link">
                                        <i class="uil uil-arrow-up-left"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>  
        <div class="container">
            <div class="row">
                <form method="POST" action="" enctype="multipart/form-data">
                <div class="geex-content__form__single__box mt-2">
                    <input type="text" name="item_name" placeholder="Nama Barang (Wajib)" class="form-control" value="<?php echo htmlspecialchars($item_name); ?>" required/>
                    <input type="text" name="product_number" placeholder="Jenis Produk (Opsional)" class="form-control" value="<?php echo htmlspecialchars($product_number); ?>"/>
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <input type="text" name="price" placeholder="Harga (Wajib)" class="form-control" value="<?php echo htmlspecialchars($price); ?>" required />
                    <input type="text" name="promo_price" placeholder="Harga Promo (Opsional)" class="form-control" value="<?php echo htmlspecialchars($promo_price); ?>" />
                    <input type="text" name="category" placeholder="Persentase Promo (Opsional)" class="form-control" value="<?php echo htmlspecialchars($category); ?>" />
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <label for="description" class="input-label">Deskripsi Barang (Wajib)</label>
                    <textarea class="form-control" id="description" name="item_description" rows="5" placeholder="Deskripsikan barang di sini..." style="border-radius: 5px;"><?php echo htmlspecialchars($item_description); ?></textarea>
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <label for="product_type" class="input-label">Jenis Produk (Wajib)</label>
                    <select id="product_type" name="product_type" class="form-control" style="border-radius: 5px; padding: 10px; font-size: 16px;" required>
                        <option value="" disabled>Pilih jenis produk</option>
                        <option value="Pupuk" <?php if ($product_type == 'pupuk') echo 'selected'; ?>>Pupuk</option>
                        <option value="Non Pupuk" <?php if ($product_type == 'non_pupuk') echo 'selected'; ?>>Non Pupuk</option>
                    </select>
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <label for="product_type" class="input-label">Stok (Wajib)</label>
                    <select id="product_type" name="stok_status" class="form-control" style="border-radius: 5px; padding: 10px; font-size: 16px;" required>
                        <option value="" disabled>Pilih status stok</option>
                        <option value="ready"<?php if ($stok_status == 1) echo 'selected'; ?>>Stok Tersedia</option>
                        <option value="Exhausted" <?php if ($stok_status == 0) echo 'selected'; ?>>Stok Habis</option>
                    </select>
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <label for="is_popular" class="input-label">Populer (Wajib)</label>
                    <select id="is_popular" name="is_popular" class="form-control" style="border-radius: 5px; padding: 10px; font-size: 16px;" required>
                        <option value="" disabled>Pilih status popularitas</option>
                        <option value="1" <?php if ($is_popular == 1) echo 'selected'; ?>>Populer</option>
                        <option value="0" <?php if ($is_popular == 0) echo 'selected'; ?>>Tidak Populer</option>
                    </select>
                </div>
                <div class="geex-content__form__single__box mt-2">
                    <label for="product_type" class="input-label">Link Produk</label>
                    <input type="text" name="whatsapp_link" placeholder="WhatsApp (Wajib)" class="form-control" value="<?php echo htmlspecialchars($whatsapp_link); ?>" required />
                    <input type="text" name="shopee_link" placeholder="Shopee (Opsional)" class="form-control" value="<?php echo htmlspecialchars($shopee_link); ?>" />
                    <input type="text" name="tokopedia_link" placeholder="Tokopedia (Opsional)" class="form-control" value="<?php echo htmlspecialchars($tokopedia_link); ?>"/>
                </div>
                <div class="geex-content__form__single__box mb-5">
                    <label for="photo_product" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                            <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                            <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                        </svg>
                        <span class="upload-text">Unggah Foto Produk</span>
                    </label>
                    <input type="file" class="form-control" name="product_photo_update" id="photo_product" style="display: none;">
                </div>
                <div class="geex-content__form__single__box mb-5">
                    <label for="photo_product_2" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                            <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                            <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                        </svg>
                        <span class="upload-text">Unggah Foto Produk 2</span>
                    </label>
                    <input type="file" class="form-control" name="product_photo_update_2" id="photo_product_2" style="display: none;">
                </div>
                <!-- Tambahkan checkbox untuk opsi menghapus gambar -->
                <div class="geex-content__form__single__box mb-5">
                    <label for="delete_product_photo_update_2">
                        <input type="checkbox" name="delete_product_photo_update_2" id="delete_product_photo_update_2" value="1">
                        Hapus Foto Produk 2
                    </label>
                </div>

                <div class="geex-content__form__single__box mb-5">
                    <label for="photo_product_3" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                            <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                            <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                        </svg>
                        <span class="upload-text">Unggah Foto Produk 3</span>
                    </label>
                    <input type="file" class="form-control" name="product_photo_update_3" id="photo_product_3" style="display: none;">
                </div>
                <!-- Tambahkan checkbox untuk opsi menghapus gambar -->
                <div class="geex-content__form__single__box mb-5">
                    <label for="delete_product_photo_update_3">
                        <input type="checkbox" name="delete_product_photo_update_3" id="delete_product_photo_update_3" value="1">
                        Hapus Foto Produk 3
                    </label>
                </div>

                <div class="geex-content__form__single__box mb-5">
                    <label for="photo_product_4" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                            <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                            <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                        </svg>
                        <span class="upload-text">Unggah Foto Produk 4</span>
                    </label>
                    <input type="file" class="form-control" name="product_photo_update_4" id="photo_product_4" style="display: none;">
                </div>
                <!-- Tambahkan checkbox untuk opsi menghapus gambar -->
                <div class="geex-content__form__single__box mb-5">
                    <label for="delete_product_photo_update_4">
                        <input type="checkbox" name="delete_product_photo_update_3" id="delete_product_photo_update_4" value="1">
                        Hapus Foto Produk 4
                    </label>
                </div>

                <div class="geex-content__form__single__box mb-5">
                    <label for="brochure_update" class="upload-label">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                            <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                            <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                            <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                        </svg>
                        <span class="upload-text">Unggah Brosur Produk</span>
                    </label>
                    <input type="file" class="form-control" name="brochure_update" id="brochure_update" style="display: none;">
                </div>
                    <div class="geex-content__form__single d-flex gap-10">
                        <button type="submit" class="geex-btn geex-btn--primary">Tambah</button>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="container mt-5">
            <h2 style="font-family: 'var(--poppins)', sans-serif; font-weight: bold; margin-bottom: 20px;">Data Produk</h2>
            <div class="table-responsive"> <!-- Wrapper for table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jenis Produk</th>
                            <th>Harga</th>
                            <th>Harga Promo</th>
                            <th>Persentase Promo</th>
                            <th>Deskripsi</th>
                            <th>Jenis Produk</th>
                            <th>Status Produk</th>
                            <th>Status Populer</th> <!-- Menambahkan kolom untuk is_populer -->
                            <th>Foto Produk</th>
                            <th>Foto Produk 2</th>
                            <th>Foto Produk 3</th>
                            <th>Foto Produk 4</th>
                            <th>Brosur Produk</th>
                            <th>Link WhatsApp</th>
                            <th>Link Shopee</th>
                            <th>Link Tokopedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($products_result->num_rows > 0) {
                            while ($row = $products_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['product_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['promo_price']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['item_description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['product_type']) . "</td>";

                                // Tampilkan status produk
                                echo "<td>";
                                echo ($row['stok_status'] == 1) ? "Stok Tersedia" : "Stok Habis";
                                echo "</td>";

                                // Tampilkan status populer
                                echo "<td>";
                                echo ($row['is_popular'] == 1) ? "Populer" : "Tidak Populer";
                                echo "</td>";

                                // Menampilkan gambar produk utama
                                $productPhotoPath = "assets/image_db/produk/" . htmlspecialchars($row['product_photo_update']);
                                echo "<td>";
                                if (file_exists($productPhotoPath) && !empty($row['product_photo_update'])) {
                                    echo "<img src='" . $productPhotoPath . "' alt='Product Image' style='max-width: 100px; height: auto; border-radius: 5px;'>";
                                } else {
                                    echo "No Image";
                                }
                                echo "</td>";

                                // Menampilkan gambar produk 2
                                $productPhotoPath2 = "assets/image_db/produk/produk2/" . htmlspecialchars($row['product_photo_update_2']);
                                echo "<td>";
                                if (file_exists($productPhotoPath2) && !empty($row['product_photo_update_2'])) {
                                    echo "<img src='" . $productPhotoPath2 . "' alt='Product Image 2' style='max-width: 100px; height: auto; border-radius: 5px;'>";
                                } else {
                                    echo "No Image";
                                }
                                echo "</td>";

                                // Menampilkan gambar produk 3
                                $productPhotoPath3 = "assets/image_db/produk/produk3/" . htmlspecialchars($row['product_photo_update_3']);
                                echo "<td>";
                                if (file_exists($productPhotoPath3) && !empty($row['product_photo_update_3'])) {
                                    echo "<img src='" . $productPhotoPath3 . "' alt='Product Image 3' style='max-width: 100px; height: auto; border-radius: 5px;'>";
                                } else {
                                    echo "No Image";
                                }
                                echo "</td>";

                                // Menampilkan gambar produk 4
                                $productPhotoPath4 = "assets/image_db/produk/produk4/" . htmlspecialchars($row['product_photo_update_4']);
                                echo "<td>";
                                if (file_exists($productPhotoPath4) && !empty($row['product_photo_update_4'])) {
                                    echo "<img src='" . $productPhotoPath4 . "' alt='Product Image 4' style='max-width: 100px; height: auto; border-radius: 5px;'>";
                                } else {
                                    echo "No Image";
                                }
                                echo "</td>";

                                // Menampilkan nama file brosur dengan ikon unduhan
                                $brochurePath = "assets/image_db/produkFile/" . htmlspecialchars($row['brochure_update']);
                                echo "<td>";
                                if (file_exists($brochurePath) && !empty($row['brochure_update'])) {
                                    echo "<a href='" . $brochurePath . "' target='_blank' class='d-flex align-items-center'>
                                            <i class='uil uil-download-alt' style='margin-right: 5px;'></i>
                                            " . htmlspecialchars($row['brochure_update']) . "
                                        </a>";
                                } else {
                                    echo "No Brochure";
                                }
                                echo "</td>";

                                // Menampilkan link WhatsApp, Shopee, Tokopedia
                                echo "<td><a href='" . htmlspecialchars($row['whatsapp_link']) . "' target='_blank'>" . htmlspecialchars($row['whatsapp_link']) . "</a></td>";
                                echo "<td><a href='" . htmlspecialchars($row['shopee_link']) . "' target='_blank'>" . htmlspecialchars($row['shopee_link']) . "</a></td>";
                                echo "<td><a href='" . htmlspecialchars($row['tokopedia_link']) . "' target='_blank'>" . htmlspecialchars($row['tokopedia_link']) . "</a></td>";
                                
                                // Tampilkan opsi aksi
                                echo "<td>
                                        <a href='productManagement.php?edit=" . htmlspecialchars($row['product_id']) . "' class='btn btn-sm btn-success'><i class='uil uil-edit'></i></a>
                                        <a href='#' class='btn btn-sm btn-danger delete-btn' data-id='" . $row['product_id'] . "'><i class='uil uil-trash-alt'></i></a>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='17'>Tidak ada data produk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Script for deleting products
(function() {
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var product_id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#246c3c',
                cancelButtonColor: '#bbc125',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "deleteProduct.php?delete=" + product_id;
                }
            });
        });
    });
})();

    // Check if success message is set
    <?php if (isset($success) && $success): ?>
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Data Berhasil Diperbarui',
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location = 'productManagement.php';
    });
    <?php endif; ?>
</script>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Data Berhasil Dihapus',
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location = 'productManagement.php'; // Redirect to clear the URL
        });
    </script>
<?php endif; ?>

<!-- inject:js-->
<?php include './partials/script.php'?>
<!-- endinject-->
</body>
</html>



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
