<!doctype html>
<html class="no-js" lang="zxx">

<?php
include "connection.php";

// Menghitung total data dari query
$query_total = "SELECT COUNT(*) AS total FROM tbl_pdk_893kk WHERE product_type = 'Non Pupuk'";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_data = $row_total['total'];

// Jumlah data per halaman (limit)
$limit = 9;

// Menghitung total halaman
$total_pages = ceil($total_data / $limit);

// Mendapatkan halaman saat ini dari URL (default halaman 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Menghitung posisi data pertama yang akan diambil untuk halaman saat ini
$start = ($page - 1) * $limit;

// Query untuk mengambil data sesuai halaman saat ini
$query_pagination = "SELECT * FROM tbl_pdk_893kk WHERE product_type = 'Non Pupuk' LIMIT $start, $limit";
$result_pagination = mysqli_query($conn, $query_pagination);

// Ambil parameter 'sort' dari URL, default ke 'latest'
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

// Tentukan urutan berdasarkan pilihan
$orderBy = 'created_at DESC'; // Default ke terbaru
if ($sort === 'oldest') {
    $orderBy = 'created_at ASC'; // Urutan dari terlama
}

function formatRupiah($number) {
    // Pastikan $number adalah angka, jika kosong berikan nilai default 0
    if (!is_numeric($number)) {
        $number = 0;
    }
    return "Rp " . number_format((float)$number, 2, ',', '.');
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>PT Maulana Raya Abadi - 2024</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="img/favicon.svg" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>

<!-- Body main wrapper start -->
<div class="wrapper">

    <!-- HEADER AREA START (header-5) -->
    <header class="ltn__header-area ltn__header-5 ltn__header-transparent-- gradient-color-4---">
        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white sticky-active-into-mobile ltn__logo-right-menu-option plr--9---">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap">
                            <div class="site-logo">
                                <a href="index.html"><img src="img/logo.svg" alt="Logo"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col header-menu-column menu-color-white---">
                        <div class="header-menu d-none d-lg-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li class=""><a href="index.php">Selamat Datang!</a>
                                        </li>
                                        <li class=""><a href="#">Perusahaan Kami</a>
                                            <ul class="mega-menu">
                                                <li><a href="#">Profil Perusahaan</a>
                                                    <ul>
                                                        <li><a href="about.php">Tentang Perusahaan</a></li>
                                                        <li><a href="visi_misi.php">Visi dan Misi</a></li>
                                                        <li><a href="history.php">Sejarah Perusahaan</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Informasi</a>
                                                    <ul>
                                                        <li><a href="berita.php">Berita</a></li>
                                                        <li><a href="karier.php">Karier</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Produk</a>
                                                    <ul>
                                                        <li><a href="product.php">Pupuk</a></li>
                                                        <li><a href="nonpupuk.php">Non Pupuk</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#"><img src="img/background/mumu_nav.svg" alt="#"></a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class=""><a href="faq.php">Tanya Mumu</a>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                <div class="ltn__header-options ltn__header-options-2 mb-sm-20">
                    <!-- Mobile Menu Button -->
                    <div class="mobile-menu-toggle d-xl-none">
                        <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ltn__header-middle-area end -->
    </header>
    <!-- HEADER AREA END -->

    <!-- Utilize Mobile Menu Start -->
    <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <div class="site-logo">
                    <a href="index.html"><img src="img/logo.svg" alt="Logo"></a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu">
                <ul>
                    <li class=""><a href="index.php">Selamat Datang!</a>
                    <li class=""><a href="faq.php">Tanya Mumu</a>
                    </li>
                    <li class=""><a href="#">Perusahaan Kami</a>
                        <ul class="mega-menu">
                            <li><a href="#">Profil Perusahaan</a>
                                <ul>
                                    <li><a href="about.php">Tentang Perusahaan</a></li>
                                    <li><a href="visi_misi.php">Visi dan Misi</a></li>
                                    <li><a href="history.php">Sejarah Perusahaan</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Informasi</a>
                                <ul>
                                    <li><a href="berita.php">Berita</a></li>
                                    <li><a href="karier.php">Karier</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Produk</a>
                                <ul>
                                    <li><a href="product.php">Pupuk</a></li>
                                    <li><a href="nonpupuk.php">Non Pupuk</a></li>
                                </ul>
                            </li>
                            <li><a href="#"><img src="img/background/mumu_nav.svg" alt="#"></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-60 bg-image plr--9---" data-bg="img/background/br_produk.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                            <h1 class="section-title white-color">Produk Pupuk</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html">Beranda</a></li>
                                <li>Pupuk</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->
    
    <!-- PRODUCT DETAILS AREA START -->
    <div class="ltn__product-area ltn__product-gutter mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__shop-options">
                        <ul>
                            <li>
                                <div class="ltn__grid-list-tab-menu ">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_product_grid"><i class="fas fa-th-large"></i></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                            <div class="showing-product-number text-right text-end">
                                    <span>Menampilkan <?php echo mysqli_num_rows($result_pagination); ?> dari <?php echo $total_data; ?> hasil</span>
                                </div> 
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="liton_product_grid">
                        <div class="ltn__product-tab-content-inner ltn__product-grid-view">
                            <div class="row">
                                    <?php
                                    if (mysqli_num_rows($result_pagination) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_pagination)) {
                                            // Use the product_photo_update field to get the relative image path
                                        $relativePathFromSQL = $row['product_photo_update']; // Example: 'assets/image_db/product.jpg'
                                        // Correct the path by prepending "../admin/"
                                        $imagePath = "../admin/assets/image_db/produk/" . $relativePathFromSQL;
                                    ?>
                                            <div class="col-xl-3 col-lg-4 col-sm-6 col-6">
                                                <div class="ltn__product-item ltn__product-item-3 text-center">
                                                    <div class="product-img">
                                                        <a href="productPupuk.php?product_id=<?php echo $row['product_id']; ?>">
                                                            <img src="<?php echo $imagePath; ?>" alt="<?php echo $row['item_name']; ?>"
                                                                style="<?php echo ($row['stok_status'] == 0) ? 'filter: grayscale(1);' : ''; ?>">
                                                        </a>
                                                        <div class="product-badge">
                                                            <ul>
                                                                <li class="sale-badge"><?php echo $row['category']; ?> %</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="product-info">
                                                        <h2 class="product-title">
                                                            <a href="productPupuk.php?product_id=<?php echo $row['product_id']; ?>">
                                                                <?php echo $row['item_name']; ?>
                                                            </a>
                                                        </h2>

                                                        <?php if ($row['stok_status'] == 1): ?>
                                                            <div class="product-price">
                                                                <?php if (!empty($row['promo_price'])) { ?>
                                                                    <!-- Jika ada promo_price, tampilkan harga promo dan harga asli -->
                                                                    <span><?php echo formatRupiah($row['promo_price']); ?></span>
                                                                    <del><?php echo formatRupiah($row['price']); ?></del>
                                                                <?php } else { ?>
                                                                    <!-- Jika promo_price kosong, hanya tampilkan harga asli -->
                                                                    <span><?php echo formatRupiah($row['price']); ?></span>
                                                                <?php } ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="product-price">
                                                                <span style="color: red; font-weight: bold;">Stok Habis</span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                        }
                                    } else {
                                        echo '<div class="col-12 text-center"><strong>Tidak ada produk yang tersedia</strong></div>';
                                    }
                                    ?>
                            </div>
                        </div>
                        </div>
                    </div> 
                    <!-- Pagination Area -->
                    <div class="ltn__pagination-area text-center pt-20 pb-20">
                        <div class="ltn__pagination">
                            <ul>
                                <!-- Tombol "First" -->
                                <?php if ($page > 1): ?>
                                    <li><a href="?page=1"><i class="fas fa-angle-double-left"></i></a></li>
                                <?php endif; ?>

                                <!-- Tombol "Previous" -->
                                <?php if ($page > 1): ?>
                                    <li><a href="?page=<?php echo $page - 1; ?>"><i class="fas fa-angle-left"></i></a></li>
                                <?php endif; ?>

                                <!-- Loop untuk menampilkan link halaman -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="<?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Tombol "Next" -->
                                <?php if ($page < $total_pages): ?>
                                    <li><a href="?page=<?php echo $page + 1; ?>"><i class="fas fa-angle-right"></i></a></li>
                                <?php endif; ?>

                                <!-- Tombol "Last" -->
                                <?php if ($page < $total_pages): ?>
                                    <li><a href="?page=<?php echo $total_pages; ?>"><i class="fas fa-angle-double-right"></i></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <!-- PRODUCT DETAILS AREA END -->

    <!-- FOOTER AREA START -->
    <footer class="ltn__footer-area  ">
        <div class="footer-top-area  section-bg-2 plr--5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-about-widget">
                            <div class="footer-logo">
                                <div class="site-logo">
                                    <img src="img/logo-2.svg" alt="Logo">
                                </div>
                            </div>
                            <p>PT. Maulana Raya Abadi adalah perusahaan distributor pupuk non-subsidi dari BUMN seperti PT. Petrokimia Gresik, Pupuk Kaltim, Pupuk Kujang, Pusri, dan juga pupuk impor lainnya.</p>
                            <div class="ltn__social-media mt-20">
                                <ul>
                                    <li><a href="https://www.facebook.com/profile.php/?id=100087041235682" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="https://www.tiktok.com/@maulanarayaabadi1" title="Tiktok"><i class="fab fab fa-tiktok"></i></a></li>
                                    <li><a href="https://www.instagram.com/maulanarayaabadi/?hl=en" title="Instagram"><i class="fab fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Profil Perusahaan</h4>
                            <div class="footer-menu">
                                <ul>
                                    <li><a href="about.php">Tentang Perusahaan</a></li>
                                    <li><a href="visi_misi.php">Visi dan Misi</a></li>
                                    <li><a href="history.php">Sejarah Perusahaan</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Informasi</h4>
                            <div class="footer-menu">
                            <ul>
                                <li><a href="berita.php">Berita</a></li>
                                <li><a href="karier.php">Karier</a></li>
                                <li class=""><a href="faq.php">Tanya Mumu</a>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Produk</h4>
                            <div class="footer-menu">
                            <ul>
                                <li><a href="product.php">Pupuk</a></li>
                                <li><a href="nonpupuk.php">Non Pupuk</a></li>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-sm-12 col-12">
                        <div class="footer-widget footer-newsletter-widget">
                            <h4 class="footer-title">Pusat Layanan Pelanggan</h4>
                            <p> Hubungi kami untuk mendapatkan layanan terbaik dan informasi terbaru melalui kontak di bawah ini.</p>
                            <div class="footer-newsletter">
                                <div class="footer-address">
                                    <ul>
                                        <li>
                                            <div class="footer-address-icon">
                                                <i class="icon-placeholder"></i>
                                            </div>
                                            <div class="footer-address-info">
                                                <p>Dsn. Payaman, Durenan, Kec. Durenan, Kabupaten Trenggalek, Jawa Timur 66381</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="footer-address-icon">
                                                <i class="icon-call"></i>
                                            </div>
                                            <div class="footer-address-info">
                                                <p><a href="tel:+6285231761006">0852-3176-1006</a></p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="footer-address-icon">
                                                <i class="icon-mail"></i>
                                            </div>
                                            <div class="footer-address-info">
                                                <p><a href="mailto:pt.maulanarayaabadi@gmail.com">pt.maulanarayaabadi@gmail.com</a></p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ltn__copyright-area ltn__copyright-2 section-bg-2 ltn__border-top-2 plr--5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="ltn__copyright-design clearfix">
                        <p>All Rights Reserved @ PT Maulana Raya Abadi <span class="current-year"></span></p>

                        <script>
                            // Script to automatically update the current year
                            document.querySelector('.current-year').textContent = new Date().getFullYear();
                        </script>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 align-self-center">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER AREA END -->

</div>
<!-- Body main wrapper end -->

    <!-- All JS Plugins -->
    <script src="js/plugins.js"></script>
    <!-- Main JS -->
    <script src="js/main.js"></script>
    <!-- Sort -->
    <script>
    document.getElementById('sort-options').addEventListener('change', function() {
        const selectedValue = this.value;
        const currentURL = new URL(window.location.href);
        currentURL.searchParams.set('sort', selectedValue);
        window.location.href = currentURL.href;
    });
    </script>

  
</body>
</html>

