

<!doctype html>
<html class="no-js" lang="zxx">

<?php

include "connection.php";

// Fetch job openings from the tbl_karier table
$sql = "SELECT posisi, deskripsi, kouta, kategori, status, link_pendaftaran, status_gaji FROM tbl_karier";
$result = $conn->query($sql);

$categories = [];
$jobs = [];

if ($result->num_rows > 0) {
    // Fetch the data
    while($row = $result->fetch_assoc()) {
        $jobs[] = $row;
        $categories[$row['kategori']][] = $row['posisi'];
    }
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
                                                        <li><a href="#">Pupuk</a>
                                                            <ul>
                                                                <li><a href="product.php">Semua Pupuk</a></li>
                                                                <li><a href="readystok_pupuk.php">Stok Tersedia</a></li>
                                                                <li><a href="readystokPromopupuk.php">Stok Promo</a></li>
                                                            </ul>
                                                        </li>
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
                            <li><a href="#">Pupuk</a>
                                <ul>
                                    <li><a href="User/product.php">Semua Pupuk</a></li>
                                    <li><a href="User/readystok_pupuk.php">Stok Tersedia</a></li>
                                    <li><a href="User/readystokPromopupuk.php">Stok Promo</a></li>
                                </ul>
                            </li>
                            <li><a href="nonpupuk.php">Non Pupuk</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-70 bg-image" data-bg="img/background/br_karier.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                            <h1 class="section-title white-color">Karier</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="index.html">Beranda</a></li>
                                <li>Karier</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- KARIR AREA START -->
    <div class="ltn__career-area pt-30 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                <div class="ltn__career-list">
                    <h2 class="section-title">Bergabunglah dengan Kami!</h2>
                    <p>Di PT Maulana Raya Abadi, kami mencari orang-orang berbakat dan penuh semangat untuk bergabung dengan tim kami. Kami menawarkan berbagai peluang karier yang dirancang untuk mendukung pertumbuhan dan perkembangan Anda.</p>
                    <p>Temukan posisi yang sesuai dengan keterampilan dan minat Anda. Jika Anda siap untuk tantangan baru dan ingin menjadi bagian dari tim kami, lihat kesempatan yang ada di bawah ini.</p>

                        <?php if (!empty($jobs)) : ?>
                            <?php foreach ($jobs as $job) : ?>
                                <div class="job-post">
                                    <h4><?php echo htmlspecialchars($job['posisi']); ?></h4>
                                    <p><?php echo htmlspecialchars($job['deskripsi']); ?></p>
                                    <span>Kouta: <?php echo htmlspecialchars($job['kouta']); ?></span>
                                    <span>Status: <?php echo htmlspecialchars($job['status']); ?></span>
                                    <span><strong><?php echo htmlspecialchars($job['status_gaji']); ?></strong></span>
                                    <a href="<?php echo htmlspecialchars($job['link_pendaftaran']); ?>" class="btn btn-primary">Melamar</a>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p><strong>Tidak ada lowongan pekerjaan saat ini.</strong></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar Start -->
                <div class="col-lg-4 col-12">
                    <div class="ltn__sidebar-area">
                        <div class="widget mt-20">
                            <h4 class="ltn__widget-title">Kategori Karier</h4>
                            <ul class="ltn__widget-menu">
                                <?php foreach ($categories as $category => $positions) : ?>
                                    <li><a href="#"><?php echo htmlspecialchars($category); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="widget">
                            <h4 class="ltn__widget-title">Kontak Kami</h4>
                            <p>Untuk informasi lebih lanjut tentang karier di PT Maulana Raya Abadi, silakan hubungi:</p>
                            <ul class="ltn__widget-contact">
                                <li><i class="icon-call"></i> <a href="tel:+6285231761006">0852-3176-1006</a></li>
                                <li><i class="icon-mail"></i> <a href="mailto:hrd@maulanarayaabadi.com">hrd@maulanarayaabadi.com</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Sidebar End -->
            </div>
        </div>
    </div>
    <!-- KARIR AREA END -->


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