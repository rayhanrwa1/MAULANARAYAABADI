<!doctype html>
<html class="no-js" lang="zxx">

<?php
// Include file koneksi
include 'connection.php';

// Ambil product_id dari URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Query untuk mengambil data produk berdasarkan product_id
$query = "SELECT product_id, brochure_update, whatsapp_link, shopee_link, tokopedia_link, product_name, product_price FROM tbl_pdk_893kk WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();

// Bind hasil dari query
$stmt->bind_result($product_id_db, $brochure_update, $whatsapp_link, $shopee_link, $tokopedia_link, $product_name, $product_price);

// Fetch data
if ($stmt->fetch()) {
    // Ambil path untuk brosur
    $brochurePath = "../admin/assets/image_db/produkFile/" . $brochure_update;

    // Cek jika brosur ada
    $brochureExists = file_exists($brochurePath);

    // Fungsi untuk memformat harga
    function formatRupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }

    // Ambil data sosial media
    // WhatsApp, Shopee, dan Tokopedia link sudah di-bind menggunakan bind_result()
    $whatsapp_link = htmlspecialchars($whatsapp_link);
    $shopee_link = htmlspecialchars($shopee_link);
    $tokopedia_link = htmlspecialchars($tokopedia_link);

} else {
    echo "Produk tidak ditemukan.";
    exit;
}

// Tutup statement
$stmt->close();
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
                                <a href="index.html"><img src="img/logo.svg" alt="Logo"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col header-menu-column menu-color-white---">
                        <div class="header-menu d-none d-lg-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                        <li class=""><a href="../index.php">Selamat Datang!</a>
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
                    <li class=""><a href="../index.php">Selamat Datang!</a>
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
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-60 bg-image" data-bg="img/background/br_produk.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                            <!-- PHP code to dynamically display item_name -->
                            <?php if ($productData) { ?>
                                <h1 class="section-title white-color"><?php echo $productData["item_name"]; ?></h1>
                            <?php } else { ?>
                                <h1 class="section-title white-color">Produk</h1>
                            <?php } ?>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html">Beranda</a></li>
                                <li><?php echo $productData ? $productData["item_name"] : "Produk"; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- SHOP DETAILS AREA START -->
    <div class="ltn__shop-details-area pb-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="ltn__shop-details-inner mb-60">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="ltn__shop-details-img-gallery">
                                    <div class="ltn__shop-details-large-img">
                                        <div class="single-large-img">
                                            <?php if ($productData) { ?>
                                                <a href="#" data-rel="lightcase:myCollection">
                                                    <img id="main-image" src="../admin/assets/image_db/produk/<?php echo $productData['product_photo_update']; ?>" alt="Image">
                                                </a>
                                            <?php } else { ?>
                                                <img src="img/product/1.png" alt="Image">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="ltn__shop-details-small-img slick-arrow-2">
                                        <?php if ($productData['product_photo_update_2']) { ?>
                                            <div class="single-small-img">
                                                <img class="small-image" src="../admin/assets/image_db/produk/produk2/<?php echo $productData['product_photo_update_2']; ?>" alt="Image" onclick="swapImage(this)">
                                            </div>
                                        <?php } ?>
                                        <?php if ($productData['product_photo_update_3']) { ?>
                                            <div class="single-small-img">
                                                <img class="small-image" src="../admin/assets/image_db/produk/produk3/<?php echo $productData['product_photo_update_3']; ?>" alt="Image" onclick="swapImage(this)">
                                            </div>
                                        <?php } ?>
                                        <?php if ($productData['product_photo_update_4']) { ?>
                                            <div class="single-small-img">
                                                <img class="small-image" src="../admin/assets/image_db/produk/produk4/<?php echo $productData['product_photo_update_4']; ?>" alt="Image" onclick="swapImage(this)">
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function swapImage(smallImg) {
                                    // Simpan gambar utama saat ini
                                    var mainImage = document.getElementById("main-image").src;
                                    
                                    // Simpan gambar kecil yang diklik
                                    var clickedSmallImg = smallImg.src;

                                    // Tukar gambar: Gambar besar menjadi gambar kecil dan sebaliknya
                                    document.getElementById("main-image").src = clickedSmallImg;
                                    smallImg.src = mainImage;
                                }
                            </script>
                            <div class="col-md-6">
                                <div class="modal-product-info shop-details-info pl-0">
                                    <?php if ($productData) { ?>
                                        <h3><?php echo $productData["item_name"]; ?></h3>
                                        <div class="product-price">
                                            <?php if ($productData['stok_status'] == 1): ?>
                                                <?php if (!empty($productData['promo_price'])) { ?>
                                                    <span><?php echo formatRupiah($productData['promo_price']); ?></span>
                                                    <del><?php echo formatRupiah($productData['price']); ?></del>
                                                <?php } else { ?>
                                                    <span><?php echo formatRupiah($productData['price']); ?></span>
                                                <?php } ?>
                                            <?php else: ?>
                                                <span style="color: red; font-weight: bold;">Stok Habis</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($productData['brochure_update'])): ?>
                                        <div class="ltn__product-details-menu-3">
                                            <ul>
                                                <li>
                                                    <a href="../admin/assets/image_db/produkFile/<?php echo htmlspecialchars($productData['brochure_update']); ?>" class="" title="Unduh Brosur" download>
                                                        <i class="fas fa-download"></i>
                                                        <span>Unduh Brosur</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    <hr>
                                    <div class="ltn__social-media">
                                        <ul>
                                            <li>Share:</li>
                                            <li>
                                                <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Cek produk ini: https://maulanarayaabadi.com/productPupuk.php?product_id=' . urlencode($productData['product_id'])); ?>" title="WhatsApp" target="_blank">
                                                    <i class="fab fa-whatsapp"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('hhttps://maulanarayaabadi.com/productPupuk.php?product_id=' . urlencode($productData['product_id'])); ?>" title="Facebook" target="_blank">
                                                    <i class="fab fa-facebook-f"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://maulanarayaabadi.com/productPupuk.php?product_id=' . urlencode($productData['product_id'])); ?>&text=<?php echo urlencode('Lihat produk keren ini!'); ?>" title="Twitter" target="_blank">
                                                    <i class="fab fa-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://maulanarayaabadi.com/productPupuk.php?product_id=' . urlencode($productData['product_id'])); ?>&title=<?php echo urlencode('Product Title'); ?>&summary=<?php echo urlencode('Product Description'); ?>" title="Linkedin" target="_blank">
                                                    <i class="fab fa-linkedin"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.instagram.com/" title="Instagram" target="_blank">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <div class="ltn__safe-checkout">
                                        <h5>Pesan sekarang!</h5>
                                        <div class="ltn__social-media">
                                            <ul>
                                                <?php
                                                // Menampilkan link WhatsApp jika ada
                                                if (!empty($whatsapp_link)) {
                                                    echo '<li>
                                                        <a href="' . htmlspecialchars($whatsapp_link, ENT_QUOTES, 'UTF-8') . '" target="_blank">
                                                            <!-- SVG untuk WhatsApp -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 31C23.732 31 30 24.732 30 17C30 9.26801 23.732 3 16 3C8.26801 3 2 9.26801 2 17C2 19.5109 2.661 21.8674 3.81847 23.905L2 31L9.31486 29.3038C11.3014 30.3854 13.5789 31 16 31ZM16 28.8462C22.5425 28.8462 27.8462 23.5425 27.8462 17C27.8462 10.4576 22.5425 5.15385 16 5.15385C9.45755 5.15385 4.15385 10.4576 4.15385 17C4.15385 19.5261 4.9445 21.8675 6.29184 23.7902L5.23077 27.7692L9.27993 26.7569C11.1894 28.0746 13.5046 28.8462 16 28.8462Z" fill="#BFC8D0"/>
                                                                <path d="M28 16C28 22.6274 22.6274 28 16 28C13.4722 28 11.1269 27.2184 9.19266 25.8837L5.09091 26.9091L6.16576 22.8784C4.80092 20.9307 4 18.5589 4 16C4 9.37258 9.37258 4 16 4C22.6274 4 28 9.37258 28 16Z" fill="url(#paint0_linear_87_7264)"/>
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 30C23.732 30 30 23.732 30 16C30 8.26801 23.732 2 16 2C8.26801 2 2 8.26801 2 16C2 18.5109 2.661 20.8674 3.81847 22.905L2 30L9.31486 28.3038C11.3014 29.3854 13.5789 30 16 30ZM16 27.8462C22.5425 27.8462 27.8462 22.5425 27.8462 16C27.8462 9.45755 22.5425 4.15385 16 4.15385C9.45755 4.15385 4.15385 9.45755 4.15385 16C4.15385 18.5261 4.9445 20.8675 6.29184 22.7902L5.23077 26.7692L9.27993 25.7569C11.1894 27.0746 13.5046 27.8462 16 27.8462Z" fill="white"/>
                                                                <path d="M12.5 9.49989C12.1672 8.83131 11.6565 8.8905 11.1407 8.8905C10.2188 8.8905 8.78125 9.99478 8.78125 12.05C8.78125 13.7343 9.52345 15.578 12.0244 18.3361C14.438 20.9979 17.6094 22.3748 20.2422 22.3279C22.875 22.2811 23.4167 20.0154 23.4167 19.2503C23.4167 18.9112 23.2062 18.742 23.0613 18.696C22.1641 18.2654 20.5093 17.4631 20.1328 17.3124C19.7563 17.1617 19.5597 17.3656 19.4375 17.4765C19.0961 17.8018 18.4193 18.7608 18.1875 18.9765C17.9558 19.1922 17.6103 19.083 17.4665 19.0015C16.9374 18.7892 15.5029 18.1511 14.3595 17.0426C12.9453 15.6718 12.8623 15.2001 12.5959 14.7803C12.3828 14.4444 12.5392 14.2384 12.6172 14.1483C12.9219 13.7968 13.3426 13.254 13.5313 12.9843C13.7199 12.7145 13.5702 12.305 13.4803 12.05C13.0938 10.953 12.7663 10.0347 12.5 9.49989Z" fill="white"/>
                                                                <defs>
                                                                    <linearGradient id="paint0_linear_87_7264" x1="26.5" y1="7" x2="4" y2="28" gradientUnits="userSpaceOnUse">
                                                                        <stop stop-color="#5BD066"/>
                                                                        <stop offset="1" stop-color="#27B43E"/>
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>
                                                        </a>
                                                    </li>';
                                                }

                                                // Menampilkan link Shopee jika ada
                                                if (!empty($shopee_link)) {
                                                    echo '<li>
                                                        <a href="' . htmlspecialchars($shopee_link, ENT_QUOTES, 'UTF-8') . '" target="_blank">
                                                            <!-- SVG untuk Shopee -->
                                                             <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="32" height="32"><path fill="#f4511e" d="M36.683,43H11.317c-2.136,0-3.896-1.679-3.996-3.813l-1.272-27.14C6.022,11.477,6.477,11,7.048,11 h33.904c0.571,0,1.026,0.477,0.999,1.047l-1.272,27.14C40.579,41.321,38.819,43,36.683,43z"/><path fill="#f4511e" d="M32.5,11.5h-2C30.5,7.364,27.584,4,24,4s-6.5,3.364-6.5,7.5h-2C15.5,6.262,19.313,2,24,2 S32.5,6.262,32.5,11.5z"/><path fill="#fafafa" d="M24.248,25.688c-2.741-1.002-4.405-1.743-4.405-3.577c0-1.851,1.776-3.195,4.224-3.195 c1.685,0,3.159,0.66,3.888,1.052c0.124,0.067,0.474,0.277,0.672,0.41l0.13,0.087l0.958-1.558l-0.157-0.103 c-0.772-0.521-2.854-1.733-5.49-1.733c-3.459,0-6.067,2.166-6.067,5.039c0,3.257,2.983,4.347,5.615,5.309 c3.07,1.122,4.934,1.975,4.934,4.349c0,1.828-2.067,3.314-4.609,3.314c-2.864,0-5.326-2.105-5.349-2.125l-0.128-0.118l-1.046,1.542 l0.106,0.087c0.712,0.577,3.276,2.458,6.416,2.458c3.619,0,6.454-2.266,6.454-5.158C30.393,27.933,27.128,26.741,24.248,25.688z"/></svg>                                          
                                                        </a>
                                                    </li>';
                                                }

                                                // Menampilkan link Tokopedia jika ada
                                                if (!empty($tokopedia_link)) {
                                                    echo '<li>
                                                        <a href="' . htmlspecialchars($tokopedia_link, ENT_QUOTES, 'UTF-8') . '" target="_blank">
                                                            <!-- SVG untuk Tokopedia -->
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="32px" height="32px" baseProfile="basic"><linearGradient id="BByzyhRg08SueoHenzjo7a" x1="32.135" x2="32.135" y1="1.445" y2="51.043" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#6dc7ff"/><stop offset=".492" stop-color="#aab9ff"/><stop offset="1" stop-color="#e6abff"/></linearGradient><path fill="url(#BByzyhRg08SueoHenzjo7a)" d="M54,13.6v24.51c0,8.79-7.12,15.91-15.9,15.91H10.27V13.6h12.59c2.93,0,6.62,1.99,9.28,4.64 c2.65-2.65,6.34-4.64,9.27-4.64H54z"/><circle cx="22.859" cy="30.163" r="9.276" fill="#fff"/><circle cx="41.411" cy="30.163" r="9.276" fill="#fff"/><path fill="#fff" d="M44,48.473c0,0.799-0.109,2.78-0.298,3.527H20.568c-0.189-0.746-0.298-2.728-0.298-3.527 C20.27,42.688,25.583,38,32.14,38C38.687,38,44,42.688,44,48.473z"/><linearGradient id="BByzyhRg08SueoHenzjo7b" x1="23.522" x2="23.522" y1="-3.418" y2="63.822" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><circle cx="23.522" cy="30.825" r="4.638" fill="url(#BByzyhRg08SueoHenzjo7b)"/><circle cx="21.203" cy="27.181" r="2.982" fill="#fff"/><path fill="#fff" d="M41.41,14.6c-2.53,0-5.97,1.74-8.57,4.34c-0.19,0.2-0.45,0.3-0.7,0.3c-0.26,0-0.52-0.1-0.71-0.3 c-2.6-2.6-6.04-4.34-8.57-4.34H11.27v38.42H38.1c8.21,0,14.9-6.69,14.9-14.91V14.6H41.41z M51,38.11 c0,7.12-5.79,12.91-12.9,12.91H13.27V16.6h9.59c1.69,0,4.69,1.29,7.15,3.76c0.57,0.56,1.32,0.88,2.13,0.88 c0.8,0,1.55-0.32,2.12-0.88c2.46-2.47,5.46-3.76,7.15-3.76H51V38.11z"/><linearGradient id="BByzyhRg08SueoHenzjo7c" x1="32.135" x2="32.135" y1="-3.418" y2="63.822" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><path fill="url(#BByzyhRg08SueoHenzjo7c)" d="M41.067,20.23 c-3.929,0-7.322,2.299-8.932,5.617c-1.61-3.318-5.003-5.617-8.933-5.617c-5.477,0-9.932,4.455-9.932,9.932s4.456,9.933,9.932,9.933 c3.929,0,7.323-2.299,8.933-5.618c1.61,3.318,5.003,5.618,8.932,5.618c5.477,0,9.933-4.456,9.933-9.933S46.544,20.23,41.067,20.23z M23.203,38.095c-4.374,0-7.932-3.559-7.932-7.933c0-4.373,3.558-7.932,7.932-7.932s7.933,3.559,7.933,7.932 C31.135,34.536,27.577,38.095,23.203,38.095z M41.067,38.095c-4.374,0-7.932-3.559-7.932-7.933c0-4.373,3.558-7.932,7.932-7.932 S49,25.789,49,30.162C49,34.536,45.441,38.095,41.067,38.095z"/><linearGradient id="BByzyhRg08SueoHenzjo7d" x1="40.749" x2="40.749" y1="-3.418" y2="63.822" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><circle cx="40.749" cy="30.825" r="4.638" fill="url(#BByzyhRg08SueoHenzjo7d)"/><circle cx="38.43" cy="27.181" r="2.982" fill="#fff"/><linearGradient id="BByzyhRg08SueoHenzjo7e" x1="31.85" x2="31.85" y1="37.11" y2="43.98" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#6dc7ff"/><stop offset=".492" stop-color="#aab9ff"/><stop offset="1" stop-color="#e6abff"/></linearGradient><path fill="url(#BByzyhRg08SueoHenzjo7e)" d="M36.57,39.3l-4.43,4.44l-0.24,0.24 l-4.77-4.77c1.14-1.29,2.82-2.1,4.67-2.1c0.12,0,0.22,0.02,0.34,0.02C33.91,37.22,35.48,38.04,36.57,39.3z"/><linearGradient id="BByzyhRg08SueoHenzjo7f" x1="31.846" x2="31.846" y1="-4.535" y2="62.706" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><path fill="url(#BByzyhRg08SueoHenzjo7f)" d="M31.9,45.278l-6.142-6.143l0.623-0.704 c1.369-1.549,3.344-2.438,5.419-2.438c0.091,0,0.175,0.006,0.258,0.014l0.133,0.007c1.997,0.102,3.82,0.994,5.135,2.515 l0.608,0.703L31.9,45.278z M28.562,39.112l3.337,3.337l3.25-3.258c-0.865-0.709-1.924-1.121-3.061-1.179 c-0.035,0.004-0.123-0.005-0.208-0.013c-0.007,0-0.015,0-0.021,0C30.619,38,29.474,38.398,28.562,39.112z"/><linearGradient id="BByzyhRg08SueoHenzjo7g" x1="32.135" x2="32.135" y1="-3.418" y2="63.822" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#1a6dff"/><stop offset="1" stop-color="#c822ff"/></linearGradient><path fill="url(#BByzyhRg08SueoHenzjo7g)" d="M54,12.6H42.264 c-0.798-4.877-5.03-8.616-10.129-8.616S22.804,7.723,22.006,12.6H10.27c-0.55,0-1,0.45-1,1v40.42c0,0.55,0.45,1,1,1H38.1 c9.32,0,16.9-7.59,16.9-16.91V13.6C55,13.05,54.55,12.6,54,12.6z M32.135,5.984c4.025,0,7.384,2.89,8.122,6.703 c-2.603,0.367-5.616,1.906-8.117,4.172c-2.51-2.266-5.523-3.805-8.126-4.172C24.751,8.875,28.11,5.984,32.135,5.984z M53,38.11 c0,8.22-6.69,14.91-14.9,14.91H11.27V14.6h11.59c2.53,0,5.97,1.74,8.57,4.34c0.19,0.2,0.45,0.3,0.71,0.3c0.25,0,0.51-0.1,0.7-0.3 c2.6-2.6,6.04-4.34,8.57-4.34H53V38.11z"/></svg>
                                                        </a>
                                                    </li>';
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Tab Start -->
                    <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2">
                        <div class="ltn__shop-details-tab-menu">
                            <div class="nav">
                                <a class="active show" data-bs-toggle="tab" href="#liton_tab_details_1_1">Deskripsi</a>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="liton_tab_details_1_1">
                                <div class="ltn__shop-details-tab-content-inner">
                                    <h4 class="title-2">Deskripsi Produk</h4>
                                    <p><?php echo nl2br(htmlspecialchars($productData['item_description'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Shop Tab End -->

                    <!-- Product Terms & Conditions Start -->
                    <div class="product-terms-conditions mt-60">
                        <h4 class="title-2">Ketentuan Pembelian</h4>
                        <ul>
                            <li>Pengembalian barang hanya diterima dalam waktu 7 hari setelah produk diterima.</li>
                            <li>Produk harus dalam keadaan baru dan belum digunakan.</li>
                            <li>Biaya pengembalian barang ditanggung oleh pembeli.</li>
                            <li>Untuk informasi lebih lanjut, hubungi customer service kami.</li>
                        </ul>
                    </div>
                    <!-- Product Terms & Conditions End -->

                </div>
            </div>
        </div>
    </div>
    <!-- SHOP DETAILS AREA END -->

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
  
</body>
</html>

