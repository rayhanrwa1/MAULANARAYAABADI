<!doctype html>
<html class="no-js" lang="zxx">

<?php
include 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Tangkpa parameter entry_id dari URL
$entry_id = isset($_GET['entry_id']) ? $_GET['entry_id'] : 0;

// Query untuk mengambil data dari tabel `tbl_media_announcements` berdasarkan entry_id
$sql = "SELECT entry_id, headline, publication_date, content_summary, image_path FROM tbl_media_announcements WHERE entry_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $entry_id);
$stmt->execute();

// Bind hasil dari query
$stmt->bind_result($entry_id_db, $headline, $publication_date, $content_summary, $relativePathFromSQL);

// Fetch data
if ($stmt->fetch()) {
    // Mengatur jalur gambar
    $imagePath = "../Admin/" . htmlspecialchars($relativePathFromSQL);

    // URL artikel untuk dibagikan
    $articleURL = urlencode('https://maulanarayaabadi.com/User/berita_detail.php?entry_id=' . urlencode($entry_id));
    $shareText = urlencode($headline); // Teks yang dibagikan
} else {
    // Jika tidak ada data, tampilkan default message
    $headline = "Berita Tidak Tersedia";
    $publication_date = "";
    $content_summary = "";
    $imagePath = "";  // Default value untuk image_path
    $articleURL = "";
    $shareText = "";
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
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-70 bg-image" data-bg="img/background/br_berita1.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                            <!-- Menampilkan headline dari database -->
                            <h1 class="section-title white-color"><?php echo $headline; ?></h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html">Beranda</a></li>
                                <li><?php echo $headline; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <div class="ltn__utilize-overlay"></div>

    <!-- PAGE DETAILS AREA START (blog-details) -->
    <div class="ltn__page-details-area ltn__blog-details-area mb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__blog-details-wrap">
                        <div class="ltn__page-details-inner ltn__blog-details-inner">
                            <h2 class="ltn__blog-title"><?php echo htmlspecialchars($headline); ?></h2>
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-date">
                                        <i class="far fa-calendar-alt"></i><?php echo htmlspecialchars($publication_date); ?>
                                    </li>
                                </ul>
                            </div>
                            <!-- Menampilkan gambar -->
                            <?php if (!empty($imagePath)): ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Image">
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($content_summary); ?></p>

                            <!-- Social Media Share -->
                            <div class="ltn__social-media">
                                <ul>
                                    <li>Share:</li>
                                    <li>
                                        <a href="https://api.whatsapp.com/send?text=<?php echo $shareText; ?>%20<?php echo $articleURL; ?>" title="WhatsApp" target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $articleURL; ?>" title="Facebook" target="_blank">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo $articleURL; ?>&text=<?php echo urlencode('Lihat artikel ini: ' . $shareText); ?>" title="Twitter" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $articleURL; ?>&title=<?php echo $shareText; ?>&summary=<?php echo urlencode($content_summary); ?>" title="Linkedin" target="_blank">
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
                        </div>
                        <hr>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE DETAILS AREA END -->

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

