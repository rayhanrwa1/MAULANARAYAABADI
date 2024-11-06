<!doctype html>
<html class="no-js" lang="zxx">

<?php
include 'User/connection.php';

// Query to fetch banner data from poster_tbl
$sql_banners = "SELECT poster_1, poster_2 FROM poster_tbl";
$result_banners = $conn->query($sql_banners);

$banners = [];
if ($result_banners->num_rows > 0) {
    while($row_banners = $result_banners->fetch_assoc()) {
        $banners[] = $row_banners;
    }
} else {
    echo "No banners found.";
}

// Query to fetch banner data from banner_tbl_index
$sql_banner2 = "SELECT product_1, product_2, product_3 FROM banner_tbl_index";
$result_banner2 = $conn->query($sql_banner2);

$banner2 = [];
if ($result_banner2->num_rows > 0) {
    while($row_banner2 = $result_banner2->fetch_assoc()) {
        $banner2[] = $row_banner2;
    }
} else {
    echo "No banners found.";
}

function formatRupiah($number) {
    // Pastikan $number adalah angka, jika kosong berikan nilai default 0
    if (!is_numeric($number)) {
        $number = 0;
    }
    return "Rp " . number_format((float)$number, 2, ',', '.');
}


// Query to fetch popular products from tbl_pdk_893kk
$sql_products = "SELECT product_id, item_name, product_photo_update, stok_status, promo_price, price FROM tbl_pdk_893kk WHERE is_popular = 1";
$result_products = $conn->query($sql_products);

$products = [];
if ($result_products->num_rows > 0) {
    while ($row_products = $result_products->fetch_assoc()) {
        $products[] = $row_products;
    }
} else {
    echo "";
}

$conn->close();
?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>PT Maulana Raya Abadi - 2024</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="/User/img/favicon.svg" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="/User/css/font-icons.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="/User/css/plugins.css">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="/User/css/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="/User/css/responsive.css">
</head>

<body>

<!-- Body main wrapper start -->
<div class="body-wrapper">

    <!-- HEADER AREA START (header-5) -->
    <header class="ltn__header-area ltn__header-5 ltn__header-transparent-- gradient-color-4---">
        <!-- ltn__header-middle-area start -->
        <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white sticky-active-into-mobile ltn__logo-right-menu-option plr--9---">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="site-logo-wrap">
                            <div class="site-logo">
                                <a href="index.html"><img src="User/img/logo.svg" alt="Logo"></a>
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
                                                        <li><a href="User/about.php">Tentang Perusahaan</a></li>
                                                        <li><a href="User/visi_misi.php">Visi dan Misi</a></li>
                                                        <li><a href="User/history.php">Sejarah Perusahaan</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Informasi</a>
                                                    <ul>
                                                        <li><a href="User/berita.php">Berita</a></li>
                                                        <li><a href="User/karier.php">Karier</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Produk</a>
                                                    <ul>
                                                        <li><a href="#">Pupuk</a>
                                                            <ul>
                                                                <li><a href="User/product.php">Semua Pupuk</a></li>
                                                                <li><a href="User/readystok_pupuk.php">Stok Tersedia</a></li>
                                                                <li><a href="User/readystokPromopupuk.php">Stok Promo</a></li>
                                                            </ul>
                                                        </li>
                                                        <li><a href="User/nonpupuk.php">Non Pupuk</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#"><img src="User/img/background/mumu_nav.svg" alt="#"></a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class=""><a href="User/faq.php">Tanya Mumu</a>
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
                    <a href="index.html"><img src="User/img/logo.svg" alt="Logo"></a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu">
                <ul>
                    <li class=""><a href="#">Selamat Datang!</a>
                    <li class=""><a href="User/faq.php">Tanya Mumu</a>
                    </li>
                    <li class=""><a href="#">Perusahaan Kami</a>
                        <ul class="mega-menu">
                            <li><a href="#">Profil Perusahaan</a>
                                <ul>
                                    <li><a href="User/about.php">Tentang Perusahaan</a></li>
                                    <li><a href="User/visi_misi.php">Visi dan Misi</a></li>
                                    <li><a href="User/history.php">Sejarah Perusahaan</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Informasi</a>
                                <ul>
                                    <li><a href="User/berita.php">Berita</a></li>
                                    <li><a href="User/karier.php">Karier</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Pupuk</a>
                                <ul>
                                    <li><a href="User/product.php">Semua Pupuk</a></li>
                                    <li><a href="User/readystok_pupuk.php">Stok Tersedia</a></li>
                                    <li><a href="User/readystokPromopupuk.php">Stok Promo</a></li>
                                </ul>
                            </li>
                            <li><a href="User/nonpupuk.php">Non Pupuk</a></li>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- SLIDER AREA START (slider-3) -->
    <div class="ltn__slider-area ltn__slider-3  section-bg-1">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-overlay-theme-black-60 bg-image" data-bg="User/img/background/page_1.jpg">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <div class="slide-video mb-50 d-none">
                                            <a class="ltn__video-icon-2 ltn__video-icon-2-border" href="https://www.youtube.com/embed/ATI7vfCgwXE?autoplay=1&showinfo=0" data-rel="lightcase:myCollection">
                                                <i class="fa fa-play"></i>
                                            </a>
                                        </div>
                                        <h1 class="slide-title animated " id="fontPT">Pupuk Non <br>   Subsidi</h1>
                                        <div class="slide-brief animated" id="fontPT">
                                            <p id="fontPT2">PT. Maulana Raya Abadi sendiri sampai saat ini bergerak dalam bidang Distributor, Trading dan Retail Pupuk Non Subsidi PT. Pupuk Indonesia dan Pupuk Import lainnya dengan memiliki jaringan pemasaran yang tersebar luas di Indonesia</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="User/product.php" class="theme-btn-1 btn btn-effect-1 text-uppercase">Lihat Produk</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ltn__slide-item -->
            <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3 ltn__slide-item-3-normal bg-overlay-theme-black-60 bg-image" data-bg="User/img/background/page_3.jpg">
                <div class="ltn__slide-item-inner  text-right text-end">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                        <h1 class="slide-title animated " id="fontPT">Pengetahuan <br>Produk </h1>
                                        <div class="slide-brief animated" >
                                            <p id="fontPT2" >Kami menyediakan fasilitas terbaik untuk penyaluran Pupuk Non Subsidi, termasuk product knowledge, pemesanan, dan pengiriman hingga ke tangan end user dengan pelayanan maksimal.</p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="User/product.php" class="theme-btn-1 btn btn-effect-1 text-uppercase">Lihat Produk</a>
                                            <a href="/User/pdf/pk.pdf" class="btn btn-transparent btn-effect-3" id="fontPT" target="_blank">Pengetahuan Produk</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
    <!-- SLIDER AREA END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <div class="ltn__feature-area mt-100 mt--65">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-6">
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="User/img/icons/svg/8-trolley.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Pengiriman Cepat</h4>
                                <p>Pengiriman cepat dan tepat waktu.</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="User/img/icons/svg/10-credit-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Pembayaran Mudah</h4>
                                <p>Proses mudah dan aman.</p>
                            </div>
                        </div>
                        <div class="ltn__feature-item ltn__feature-item-8">
                            <div class="ltn__feature-icon">
                                <img src="User/img/icons/svg/11-gift-card.svg" alt="#">
                            </div>
                            <div class="ltn__feature-info">
                                <h4>Banyak Promo</h4>
                                <p>Diskon besar di setiap kesempatan </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- BANNER SECTION START -->
    <div class="banner-area pt-20">
        <div class="container">
            <div class="row">
                <?php if (!empty($banner2)): ?>
                    <?php foreach ($banner2 as $banner): ?>
                        <div class="col-md-4">
                            <img src="/Admin/assets/image_db/banner/<?php echo htmlspecialchars($banner['product_1']); ?>" alt="Banner 1">
                        </div>
                        <div class="col-md-4">
                            <img src="/Admin/assets/image_db/banner/<?php echo htmlspecialchars($banner['product_2']); ?>" alt="Banner 2">
                        </div>
                        <div class="col-md-4">
                            <img src="/Admin/assets/image_db/banner/<?php echo htmlspecialchars($banner['product_3']); ?>" alt="Banner 3">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No banners found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- BANNER SECTION END -->

    <!-- FEATURE AREA START ( Feature - 3) -->
    <div class="ltn__feature-area pt-115 pb-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 section-title-style-3">
                        <div class="section-brief-in">
                            <p>PT. Maulana Raya Abadi bergerak dalam bidang Distributor, Trading dan Retail untuk Pemasaran Pupuk Non Subsidi, dengan jangkauan distribusi luas di Indonesia, didukung oleh tim yang profesional dan berpengalaman.</p>
                        </div>
                        <div class="section-title-in">
                            <h6 class="section-subtitle ltn__secondary-color">// Kenapa Memilih Kami</h6>
                            <h1 class="section-title">Keuntungan Ekstra<span>.</span></h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3 text-right text-end">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-leaf"></i></span> <!-- Ikon daun -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Produk Terbaik</a></h3>
                                    <p>Produk pupuk berkualitas terbaik untuk kebutuhan sektor pertanian Anda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3 text-right text-end">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-globe"></i></span> <!-- Ikon globe -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Distribusi Luas</a></h3>
                                    <p>Jaringan distribusi yang tersebar luas di Jawa dan luar Jawa.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3 text-right text-end">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-users"></i></span> <!-- Ikon tim -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Tim Profesional</a></h3>
                                    <p>Tim yang berdedikasi tinggi dan berpengalaman dalam bidang distribusi pupuk.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-banner-img text-center mb-30">
                        <img src="User/img/background/whyc.svg" alt="#">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-handshake"></i></span> <!-- Ikon layanan -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Layanan Terbaik</a></h3>
                                    <p>Kami memberikan pelayanan maksimal dari awal hingga pupuk sampai di tangan Anda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-check-circle"></i></span> <!-- Ikon kredibilitas -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Kredibilitas Terjamin</a></h3>
                                    <p>Memiliki kredibilitas tinggi dalam menyediakan produk pupuk terbaik.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-6 col-12">
                            <div class="ltn__feature-item ltn__feature-item-3">
                                <div class="ltn__feature-icon">
                                    <span><i class="fas fa-award"></i></span> <!-- Ikon kualitas -->
                                </div>
                                <div class="ltn__feature-info">
                                    <h3><a href="service-details.html">Komitmen pada Kualitas</a></h3>
                                    <p>Kami berkomitmen untuk terus memberikan produk berkualitas kepada pelanggan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FEATURE AREA END -->

    <!-- BANNER AREA START -->
    <!-- <div class="ltn__banner-area mt-120 mb-90">
        <div class="container">
            <div class="row ltn__custom-gutter--- justify-content-center">
                <?php if (!empty($banners)) : ?>
                    <?php foreach ($banners as $banner) : ?>
                        <div class="col-lg-6 col-md-6">
                            <div class="ltn__banner-item">
                                <div class="ltn__banner-img">
                                    <?php
                                    // Path for poster_1
                                    $relativePathFromSQL1 = htmlspecialchars($banner['poster_1']);
                                    $imagePath1 = "User/admin/assets/image_db/userPage/" . $relativePathFromSQL1;
                                    ?>
                                    <a href="product.php"><img src="<?php echo $imagePath1; ?>" alt="Banner Image"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="ltn__banner-item">
                                <div class="ltn__banner-img">
                                    <?php
                                    // Path for poster_2
                                    $relativePathFromSQL2 = htmlspecialchars($banner['poster_2']);
                                    $imagePath2 = "User/admin/assets/image_db/userPage/" . $relativePathFromSQL2;
                                    ?>
                                    <a href="product.php"><img src="<?php echo $imagePath2; ?>" alt="Banner Image"></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No banners available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div> -->
    <!-- BANNER AREA END -->

    <!-- PRODUCT AREA START (product-item-3) -->
    <div class="ltn__product-area ltn__product-gutter pt-115 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2 text-center">
                        <h1 class="section-title">Produk Unggulan</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <!-- Loop through products -->
                <?php foreach ($products as $row): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <div class="ltn__product-item ltn__product-item-3 text-left">
                            <div class="product-img">
                                <?php
                                // Correct image path
                                $relativePathFromSQL = $row['product_photo_update'];
                                $imagePath = "/Admin/assets/image_db/produk/" . $relativePathFromSQL;
                                ?>
                                <a href="User/productPupuk.php?product_id=<?php echo $row['product_id']; ?>"><img src="<?php echo $imagePath; ?>" alt="#"></a>
                                <div class="product-badge">
                                    <ul>
                                        <li class="sale-badge">Unggulan</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="product-info">
                                <h2 class="product-title">
                                    <a href="/User/productPupuk.php?product_id=<?php echo $row['product_id']; ?>">
                                        <?php echo $row['item_name']; ?>
                                    </a>
                                </h2>

                                <?php if ($row['stok_status'] == 1): ?>
                                    <div class="product-price">
                                        <?php if (!empty($row['promo_price'])) { ?>
                                            <!-- Display promo price and original price if promo exists -->
                                            <span><?php echo formatRupiah($row['promo_price']); ?></span>
                                            <del><?php echo formatRupiah($row['price']); ?></del>
                                        <?php } else { ?>
                                            <!-- Display only original price -->
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- PRODUCT AREA END -->

    <!-- BRAND LOGO AREA START -->
    <div class="ltn__brand-logo-area ltn__brand-logo-1 section-bg-6 border-top pt-35 pb-35 plr--9">
        <div class="container-fluid">
            <div class="row ltn__brand-logo-active">
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/kujang.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/multiMas.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/petrokimiaGresik.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/mestIndo.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/pupukKaltim.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/permataAgro.svg" alt="Brand Logo">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="User/img/brand-logo/agroman.svg" alt="Brand Logo">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BRAND LOGO AREA END -->

    <!-- GOOGLE MAP AREA START -->
    <div class="google-map mt-60 mb-120">
       
       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4968.082902988329!2d111.8053955759356!3d-8.123836091905659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e791da9f020acc3%3A0x7b0269b3413a9684!2sMaulana%20Raya%20Abadi%20%5BMRA%5D!5e1!3m2!1sid!2sid!4v1726412090814!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

    </div>

    <!-- FOOTER AREA START -->
    <footer class="ltn__footer-area  ">
        <div class="footer-top-area  section-bg-2 plr--5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-about-widget">
                            <div class="footer-logo">
                                <div class="site-logo">
                                    <img src="User/img/logo-2.svg" alt="Logo">
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
                                    <li><a href="User/about.php">Tentang Perusahaan</a></li>
                                    <li><a href="User/visi_misi.php">Visi dan Misi</a></li>
                                    <li><a href="User/history.php">Sejarah Perusahaan</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Informasi</h4>
                            <div class="footer-menu">
                            <ul>
                                <li><a href="User/berita.php">Berita</a></li>
                                <li><a href="User/karier.php">Karier</a></li>
                                <li class=""><a href="User/faq.php">Tanya Mumu</a>
                            </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6 col-sm-6 col-12">
                        <div class="footer-widget footer-menu-widget clearfix">
                            <h4 class="footer-title">Produk</h4>
                            <div class="footer-menu">
                            <ul>
                                <li><a href="User/product.php">Pupuk</a></li>
                                <li><a href="User/nonpupuk.php">Non Pupuk</a></li>
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
    <script src="User/js/plugins.js"></script>
    <!-- Main JS -->
    <script src="User/js/main.js"></script>
  
</body>
</html>

