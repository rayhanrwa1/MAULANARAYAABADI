<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = 'DefaultPassDB';
include './partials/head.php';
include 'connection.php'; // Include koneksi ke database
// Inisialisasi variabel untuk error
$error = '';
$success = '';
$username = ''; // Variabel untuk menyimpan username atau emp_number yang berhasil login

session_start();

// Cek apakah user yang mengakses halaman ini memiliki password default
if (!isset($_SESSION['username_employee']) || $_SESSION['password_employee'] !== 'MRAdef4ult') {
    $error = 'Akses Ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.';
} else {
    // Proses reset kata sandi
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newPassword = $_POST['loginPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Validasi password baru (minimal 8 karakter, harus ada uppercase, lowercase, dan simbol)
        if (strlen($newPassword) >= 8 && 
            preg_match('/[A-Z]/', $newPassword) && 
            preg_match('/[a-z]/', $newPassword) && 
            preg_match('/[\W_]/', $newPassword)) {
            
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password di database
                $updateStmt = $conn->prepare("UPDATE tbl_emp_7tt8 SET password_employee = ?, is_active = 1 WHERE username_employee = ?");
                $updateStmt->bind_param("ss", $hashedPassword, $_SESSION['username_employee']);
                if ($updateStmt->execute()) {
                    $success = 'Kata Sandi Berhasil Direset! Silakan login ulang dengan kata sandi baru.';
                    session_destroy(); // Hapus sesi
                } else {
                    $error = 'Terjadi kesalahan saat mereset kata sandi. Silakan coba lagi.';
                }
                $updateStmt->close();
            } else {
                $error = 'Kata sandi dan konfirmasi kata sandi tidak cocok.';
            }
        } else {
            $error = 'Kata sandi harus terdiri dari minimal 8 karakter, termasuk huruf besar, huruf kecil, dan simbol.';
        }
    }
}

$conn->close();
?>
<body class="geex-dashboard authentication-page">
    <main class="geex-content">
        <div class="geex-content__authentication">
            <div class="geex-content__authentication__content">
                <div class="geex-content__authentication__content__wrapper">
                    <div class="geex-content__authentication__content__logo">
                        <a href="index.php">
                            <img class="logo-lite" src="assets/img/logo.svg" alt="logo">
                            <img class="logo-dark" src="assets/img/logo.svg" alt="logo">
                        </a>
                    </div>
                    <form id="signInForm" class="geex-content__authentication__form" method="POST" action="">
                        <div class="geex-content__authentication__form-group">
                            <div class="geex-content__authentication__label-wrapper">
                                <label for="loginPassword">Kata Sandi Baru</label>
                            </div>
                            <input type="password" id="loginPassword" name="loginPassword" placeholder="Masukan Kata Sandi Baru" required>
                            <i class="uil-eye toggle-password-type"></i>
                        </div>
                        <div class="geex-content__authentication__form-group">
                            <div class="geex-content__authentication__label-wrapper">
                                <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                            </div>
                            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi Kata Sandi Baru" required>
                            <i class="uil-eye toggle-password-type"></i>
                        </div>
                        <button type="submit" class="geex-content__authentication__form-submit">Reset Kata Sandi</button>
                    </form>
                </div>
            </div>    
            <div id="authCarousel" class="carousel slide carousel-fade geex-content__authentication__img" data-bs-ride="carousel">
                <!-- Indicators/dots -->
                <ol class="carousel-indicators">
                    <li data-bs-target="#authCarousel" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#authCarousel" data-bs-slide-to="1"></li>
                    <li data-bs-target="#authCarousel" data-bs-slide-to="2"></li>
                </ol>

                <!-- The slideshow/carousel -->
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="./assets/img/background-signin.svg" class="d-block w-100" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/img/background-signin2.svg" class="d-block w-100" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img src="./assets/img/background-signin3.svg" class="d-block w-100" alt="Third slide">
                    </div>
                </div>

                <!-- Left and right controls/icons -->
                <a class="carousel-control-prev" href="#authCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#authCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>
            </div>
        </div>
    </main>

    <!-- inject:js-->
    <?php include './partials/script.php' ?>
    <!-- endinject-->
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/autoLogout.js"></script>
    <script src="assets/js/noInternetcon.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($error)) { ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $error; ?>',
                    showConfirmButton: false,
                    timer: 2500,
                }).then(function() {
                    <?php if ($error === 'Akses Ditolak! Anda tidak memiliki izin untuk mengakses halaman ini.') { ?>
                        window.location = 'index.php';
                    <?php } ?>
                });
            <?php } else if (!empty($success)) { ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Kata Sandi Berhasil Direset!',
                    text: '<?php echo $success; ?>',
                    showConfirmButton: false,
                    timer: 2500,
                }).then(function() {
                    window.location = 'index.php';
                });
            <?php } ?>
        });
    </script>
    <!-- End SweetAlert -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Swipe and Click Gesture Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carousel = document.querySelector('#authCarousel');
            let startX, endX;

            // Function to handle swipe gestures
            const handleGesture = () => {
                const threshold = 50;
                if (startX - endX > threshold) {
                    bootstrap.Carousel.getInstance(carousel).next();
                } else if (endX - startX > threshold) {
                    bootstrap.Carousel.getInstance(carousel).prev();
                }
            };

            // Mouse events
            carousel.addEventListener('mousedown', function(e) {
                startX = e.clientX;
            });

            carousel.addEventListener('mouseup', function(e) {
                endX = e.clientX;
                handleGesture();
            });

            // Touch events
            carousel.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
            });

            carousel.addEventListener('touchend', function(e) {
                endX = e.changedTouches[0].clientX;
                handleGesture();
            });

            // Prevents touch events from affecting the page scroll
            carousel.addEventListener('touchmove', function(e) {
                e.preventDefault();
            }, { passive: false });
        });
    </script>
</body>
</html>
