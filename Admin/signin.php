<?php
$title = 'Signin';
include './partials/head.php';
include 'connection.php'; // Include koneksi ke database

session_start(); // Mulai sesi

// Cek jika pengguna sudah login
if (isset($_SESSION['username_employee'])) {
    // Jika sudah login, arahkan ke halaman yang sesuai, misalnya dashboard
    header('Location: index.php'); // Ubah ke halaman yang sesuai
    exit();
}

// Inisialisasi variabel untuk error
$error = '';
$success = '';
$username = ''; // Variabel untuk menyimpan username atau emp_number yang berhasil login
$lastLogin = ''; // Variabel untuk menyimpan waktu login terakhir

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmpNumber = $_POST['emailSignIn'];
    $password = $_POST['loginPassword'];

    // Prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("SELECT username_employee, password_employee, last_login FROM tbl_emp_7tt8 WHERE username_employee = ? OR emp_number = ?");
    $stmt->bind_param("ss", $usernameOrEmpNumber, $usernameOrEmpNumber);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($username_employee, $hashed_password, $last_login);
        $stmt->fetch();

        // Verifikasi password dengan hash yang disimpan
        if (password_verify($password, $hashed_password)) {
            // Jika login berhasil
            $_SESSION['username_employee'] = $username_employee;
            $_SESSION['password_employee'] = $password; // Simpan password asli ke dalam sesi

            // Update last_login dan is_active
            $updateStmt = $conn->prepare("UPDATE tbl_emp_7tt8 SET last_login = NOW(), is_active = 1 WHERE username_employee = ?");
            $updateStmt->bind_param("s", $username_employee);
            $updateStmt->execute();
            $updateStmt->close();

            // Menampilkan SweetAlert di tengah
            $success = true;
            $lastLogin = date('d M Y H:i:s', strtotime($last_login));
        } else {
            // Jika password salah
            $error = 'Username/Emp Number atau Password salah';
        }
    } else {
        // Jika username atau emp_number tidak ditemukan
        $error = 'Username/Emp Number atau Password salah';
    }
    $stmt->close();
    $conn->close();
}
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
                            <label for="emailSignIn">Nomor Identifikasi Karyawan</label>
                            <input type="text" id="emailSignIn" name="emailSignIn" placeholder="Nomor Identifikasi Karyawan" required>
                            <i class="uil uil-user"></i>
                        </div>
                        <div class="geex-content__authentication__form-group">
                            <div class="geex-content__authentication__label-wrapper">
                                <label for="loginPassword">Kata Sandi</label>
                                <a href="forget-password.php">Lupa Kata Sandi</a>
                            </div>
                            <input type="password" id="loginPassword" name="loginPassword" placeholder="Kata Sandi" required>
                            <i class="uil-eye toggle-password-type"></i>
                        </div>
                        <button type="submit" class="geex-content__authentication__form-submit">Masuk</button>
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
    <script>
        <?php if (!empty($error)) { ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Maaf, Password atau NIK yang Anda masukkan salah. Silakan coba lagi.',
            confirmButtonText: 'Ok',
            confirmButtonColor: '#246c3c',
            footer: '<a href="#">Hubungi IT jika mengalami kesulitan</a>'
        });
        <?php } elseif (!empty($success)) { ?>
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Login Berhasil!",
                text: "Username: <?php echo $username_employee; ?>\nLast Login: <?php echo $lastLogin; ?>",
                showConfirmButton: false,
                timer: 2500
            }).then(function() {
                // Cek apakah password yang digunakan adalah password default
                if ('<?php echo $_SESSION['password_employee']; ?>' === 'MRAdef4ult') {
                    window.location = "recoveryDB.php"; // Redirect ke halaman recoveryDB.php
                } else {
                    window.location = "index.php"; // Redirect ke halaman index.php
                }
            });
        <?php } ?>
    </script>
    <!-- End SweetAlert -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/noInternetcon.js"></script>
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
