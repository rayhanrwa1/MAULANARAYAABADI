<!doctype html>
<html lang="en" dir="ltr">
<?php
$title = 'Product Overview'; 
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

$username = $_SESSION['username_employee'];
$stmt = $conn->prepare("SELECT employee_name, position_name, photo FROM tbl_emp_7tt8 WHERE username_employee = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($employee_name, $position_name, $photo);
$stmt->fetch();
$stmt->close();

if (empty($employee_name)) {
    $employee_name = "Unknown User";
    $position_name = "Unknown Position";
    $photo = "assets/img/avatar/user_profile.svg";
} else {
    $employee_name = htmlspecialchars($employee_name);
    $position_name = htmlspecialchars($position_name);
    $photo = !empty($photo) ? htmlspecialchars($photo) : "assets/img/avatar/user_profile.svg";
}

// Determine sort order
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Adjust SQL query based on sort order
if ($sort_order == 'available') {
    $sql = "SELECT item_name, price, promo_price, product_number, item_description, product_photo_update, whatsapp_link, shopee_link, tokopedia_link, brochure_update, stok_status FROM tbl_pdk_893kk WHERE stok_status = 1 ORDER BY product_id DESC";
} elseif ($sort_order == 'unavailable') {
    $sql = "SELECT item_name, price, promo_price, product_number, item_description, product_photo_update, whatsapp_link, shopee_link, tokopedia_link, brochure_update, stok_status FROM tbl_pdk_893kk WHERE stok_status = 0 ORDER BY product_id DESC";
} else {
    $sql = "SELECT item_name, price, promo_price, product_number, item_description, product_photo_update, whatsapp_link, shopee_link, tokopedia_link, brochure_update, stok_status FROM tbl_pdk_893kk ORDER BY product_id " . ($sort_order == 'oldest' ? 'ASC' : 'DESC');
}

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

// Check if query execution was successful
if ($notificationsResult === FALSE) {
    echo "Error: " . $conn->error;
    $notifications = [];
} else {
    // Fetch all rows
    $notifications = [];
    while ($row = $notificationsResult->fetch_assoc()) {
        $notifications[] = $row;
    }
}


function truncateDescription($description, $limit = 30) {
    $words = explode(' ', $description);
    if (count($words) > $limit) {
        $first_part = implode(' ', array_slice($words, 0, ceil($limit / 2)));
        $second_part = implode(' ', array_slice($words, ceil($limit / 2)));
        return [$first_part, $second_part];
    } else {
        return [$description, null];
    }
}

?>

<body class="geex-dashboard">
<?php include './partials/header.php'; ?>
<main class="geex-main-content">
<?php include './partials/sidebar.php'; ?>
<?php include './partials/customizer.php'; ?>

    <div class="geex-content">
        <div class="geex-content__header">
            <div class="geex-content__header__content">
                <h2 class="geex-content__header__title">Halaman Review Product</h2>
                <p class="geex-content__header__subtitle">Halaman Review Product of PT Maulana Raya Abadi</p>
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

        <!-- Dropdown for Sorting -->
        <div class="dropdown">
            <button class="dropdown-btn">Sortir <i class="uil uil-sort"></i></button>
            <div class="dropdown-content">
                <a href="?sort=newest" class="dropdown-item" data-sort="newest">Terbaru</a>
                <a href="?sort=oldest" class="dropdown-item" data-sort="oldest">Terlama</a>
            </div>
        </div>
        
        <!-- Dropdown for Stock Status -->
        <div class="dropdown-stok">
            <button class="dropdown-btn-stok">Stok <i class="uil uil-sort"></i></button>
            <div class="dropdown-content-stok">
                <a href="?sort=available" class="product-item" data-stock-status="tersedia">Tersedia</a>
                <a href="?sort=unavailable" class="product-item" data-stock-status="tidak tersedia">Tidak Tersedia</a>
            </div>
        </div>
     

        <div class="geex-content__blog">
            <div class="geex-content__blog__wrapper">
                <div class="row">
                <?php
                if ($products_result->num_rows > 0) {
                    while($row = $products_result->fetch_assoc()) {
                        $promo_price = $row['promo_price'];
                        $product_number = $row['product_number'];
                        $whatsapp_link = $row['whatsapp_link'] ? $row['whatsapp_link'] : '';
                        $shopee_link = $row['shopee_link'] ? $row['shopee_link'] : '';
                        $tokopedia_link = $row['tokopedia_link'] ? $row['tokopedia_link'] : '';
                        $brochure_link = $row['brochure_update'] ? $row['brochure_update'] : '';
                        $item_description = htmlspecialchars($row['item_description'], ENT_QUOTES, 'UTF-8');
                        $item_description = nl2br($item_description);
                        $stok_status = $row['stok_status'];
                        
                        // Setiap blok produk dibungkus dalam div ini
                        echo '<div class="col-xxl-3 col-xl-4 col-md-6 mb-25">';
                        echo '    <div class="geex-content__blog__single">';
                        echo '        <div class="geex-content__blog__single__img">';
                        echo '            <a>';
                        echo '                <img src="assets/image_db/produk/'.$row['product_photo_update'].'" alt="Product Image" />';
                        echo '            </a>';
                        echo '        </div>';
                        echo '        <div class="geex-content__blog__single__content">';
                        echo '            <div class="geex-content__blog__single__quickinfo">';
                        echo '                <h5>'.$row['item_name'].'</h5>';
                        echo '                <strong>';
                        if ($stok_status == 0) {
                            echo '<span style="color:red; font-size: 17px;">Stok Habis</span>';
                        } else {
                            if ($promo_price) {
                                echo ' <del>Rp '.number_format($row['price'], 0, ',', '.').'</del>';
                                echo ' <a style="color:red;">Rp '.number_format($promo_price, 0, ',', '.').'</a>';
                            } else {
                                echo 'Rp '.number_format($row['price'], 0, ',', '.');
                            }
                        }
                        echo '                </strong>';
                        echo '            </div>';
                        echo '            <div class="product-category">';
                        echo '                <strong> Jenis Produk : '.$row['product_number'].'</strong>';
                        echo '            </div>';
                        echo '        </div>';
                        echo '<div class="geex-content__blog__single__title">';

                        // Mendapatkan deskripsi singkat (30 karakter pertama)
                        $short_description = substr(strip_tags($item_description), 0, 30);
                        
                        // Memeriksa apakah deskripsi lengkap lebih dari 30 karakter
                        $has_more = strlen(strip_tags($item_description)) > 30;
                        
                        // Menampilkan deskripsi singkat
                        echo '<p class="short-description">'.$short_description;
                        if ($has_more) {
                            echo ' <span class="read-more" onclick="toggleDescription(this)" style="font-weight: bold; color: #d8d149; cursor: pointer;">Lihat selengkapnya...</span></p>';
                            
                            // Menampilkan deskripsi lengkap tersembunyi
                            echo '<p class="full-description" style="display: none;">'.$item_description.' <span class="read-less" onclick="toggleDescription(this)" style="font-weight: bold; color: #d8d149; cursor: pointer;">Lihat lebih sedikit</span></p>';
                        } else {
                            echo '</p>'; // Tutup paragraf jika tidak ada deskripsi lebih
                        }
                        
                        echo '</div>';
                        
                        
                        // Social media links
                        echo '        <div class="social-icons mt-20">';
                        if ($whatsapp_link) {
                            echo ' <a href="'.$whatsapp_link.'" target="_blank" style="display: block; width: 100%; text-align: center; text-decoration: none;">';
                            echo '     <button style="display:flex; align-items:center; justify-content: center; background-color:#25d366; color:white; border:none; border-radius:5px; padding:10px 20px; margin-bottom:5px; width: calc(100% - 20px); box-sizing: border-box; transition: all 0.3s ease;">';
                            echo '         <img src="assets/icons/wa.svg" alt="WhatsApp" style="width:20px; height:20px; margin-right:10px; "/>';
                            echo '         Beli di WhatsApp';
                            echo '     </button>';
                            echo ' </a>';
                        }
                        if ($shopee_link) {
                            echo ' <a href="'.$shopee_link.'" target="_blank" style="display: block; width: 100%; text-align: center; text-decoration: none;">';
                            echo '     <button style="display:flex; align-items:center; justify-content: center; background-color:#ff5722; color:white; border:none; border-radius:5px; padding:10px 20px; margin-bottom:5px; width: calc(100% - 20px); box-sizing: border-box; transition: all 0.3s ease;">';
                            echo '         <img src="assets/icons/shopee.svg" alt="Shopee" style="width:20px; height:20px; margin-right:10px;"/>';
                            echo '         Beli di Shopee';
                            echo '     </button>';
                            echo ' </a>';
                        }
                        if ($tokopedia_link) {
                            echo ' <a href="'.$tokopedia_link.'" target="_blank" style="display: block; width: 100%; text-align: center; text-decoration: none;">';
                            echo '     <button style="display:flex; align-items:center; justify-content: center; background-color:#42b549; color:white; border:none; border-radius:5px; padding:10px 20px; margin-bottom:5px; width: calc(100% - 20px); box-sizing: border-box; transition: all 0.3s ease;">';
                            echo '         <img src="assets/icons/tokopedia.svg" alt="Tokopedia" style="width:20px; height:20px; margin-right:10px;"/>';
                            echo '         Beli di Tokopedia';
                            echo '     </button>';
                            echo ' </a>';
                        }
                        echo '        </div>';
                      // Brochure download link
                        if ($brochure_link) {
                            echo '<div class="brochure-download mt-20">';
                            echo '    <a href="assets/brochures/'.$brochure_link.'" download class="brochure-link">';
                            echo '        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none">';
                            echo '            <path opacity="0.5" d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
                            echo '            <path d="M12 3V16M12 16L16 11.625M12 16L8 11.625" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
                            echo '        </svg>';
                            echo '        Download Brosur';
                            echo '    </a>';
                            echo '</div>';
                        }
                            echo '    </div>'; // End of .geex-content__blog__single
                            echo '</div>'; // End of .col-xxl-3
                            }
                        } else {
                            echo "Tidak ada produk yang tersedia.";
                        }
                    ?>                
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const entry_id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#246c3c',
                cancelButtonColor: '#bbc125',
                confirmButtonText: 'Ya, hapus!',
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = "?delete=" + entry_id;
                }
            });
        });
    });

    // Check if success message is set
    <?php if (isset($success) && $success): ?>
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Data berhasil disimpan!',
        showConfirmButton: false,
        timer: 1500
    }).then(() => {
        window.location = 'newsOverview.php';
    });
    <?php endif; ?>

    // Dropdown untuk sorting
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    const savedSort = localStorage.getItem('sortOrder') || 'newest';
    
    dropdownItems.forEach(item => {
        if (item.getAttribute('data-sort') === savedSort) {
            dropdownBtn.textContent = item.textContent;
            dropdownBtn.innerHTML = `${item.textContent} <i class="uil uil-sort"></i>`;
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });

    dropdownBtn.addEventListener('click', () => {
        document.querySelector('.dropdown').classList.toggle('show');
    });

    dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
            const sortOrder = item.getAttribute('data-sort');
            localStorage.setItem('sortOrder', sortOrder);
            dropdownBtn.textContent = item.textContent;
            dropdownBtn.innerHTML = `${item.textContent} <i class="uil uil-sort"></i>`;
            dropdownItems.forEach(i => i.classList.remove('selected'));
            item.classList.add('selected');
        });
    });

    // Dropdown untuk status stok
    const dropdownBtnStok = document.querySelector('.dropdown-btn-stok');
    const dropdownContentStok = document.querySelector('.dropdown-content-stok');
    const dropdownItemsStok = document.querySelectorAll('.product-item');

    dropdownBtnStok.addEventListener('click', () => {
        dropdownContentStok.classList.toggle('show');
    });

    dropdownItemsStok.forEach(item => {
    item.addEventListener('click', event => {
        const stockStatus = item.getAttribute('data-stock-status');
        filterStock(stockStatus);
        // Biarkan navigasi terjadi secara alami
        });
    });

    function filterStock(status) {
    console.log('Filtering stock with status:', status); // Debug log
    const products = document.querySelectorAll('.geex-content__blog__single');
    products.forEach(product => {
        const statusElement = product.querySelector('.geex-content__blog__single__quickinfo span');
        const productStatus = statusElement ? statusElement.textContent.trim().toLowerCase() : '';

        console.log('Product status:', productStatus); // Debug log

        if ((status === 'tersedia' && productStatus === 'stok habis') || 
            (status === 'tidak tersedia' && productStatus !== 'stok habis')) {
            product.style.display = 'none';
        } else {
            product.style.display = 'block';
        }
    });
}



    window.addEventListener('click', event => {
        if (!event.target.matches('.dropdown-btn, .dropdown-btn-stok')) {
            document.querySelectorAll('.dropdown.show, .dropdown-content-stok.show').forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
});

function toggleDescription(element) {
    console.log("Toggle button clicked!");

    var parentElement = element.parentElement;
    var container = parentElement.parentElement; // Kontainer deskripsi

    // Mengambil deskripsi singkat dan deskripsi penuh
    var shortDescription = container.querySelector('.short-description');
    var fullDescription = container.querySelector('.full-description');

    // Debugging untuk memeriksa elemen yang diambil
    console.log("Parent Element:", parentElement);
    console.log("Short Description:", shortDescription);
    console.log("Full Description:", fullDescription);

    if (shortDescription && fullDescription) {
        if (fullDescription.style.display === "none") {
            // Tampilkan deskripsi penuh, sembunyikan deskripsi singkat
            fullDescription.style.display = "block";
            shortDescription.style.display = "none";
        } else {
            // Sembunyikan deskripsi penuh, tampilkan deskripsi singkat
            fullDescription.style.display = "none";
            shortDescription.style.display = "block";
        }
    } else {
        console.log("Element not found or structure not as expected.");
    }
}




</script>

<!-- inject:js-->
<?php include './partials/script.php'?>
<!-- endinject-->

</body>
</html>



