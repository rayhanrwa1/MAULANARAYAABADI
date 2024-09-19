<!doctype html>
<html lang="en" dir="ltr">

<?php
$title = 'Profile';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username_employee'])) {
    // If not logged in, redirect to login page
    header('Location: signin.php');
    exit();
}

include './partials/head.php';
include 'connection.php'; // Include the database connection

// Fetch the user data
$username = $_SESSION['username_employee'];
$sql = "SELECT employee_name, position_name, photo, emp_number, employee_phone, employee_address, join_date FROM tbl_emp_7tt8 WHERE username_employee = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();

// Bind result variables
$stmt->bind_result($employee_name, $position_name, $photo, $emp_number, $employee_phone, $employee_address, $join_date);

// Fetch the results
if ($stmt->fetch()) {
    // If the user has a photo, use it; otherwise, use the default image
    $photo = $photo ? $photo : "assets/img/avatar/user_profile.svg";
} else {
    // Handle the case where the user data could not be found
    $employee_name = "Unknown User";
    $position_name = "Unknown Position";
    $photo = "assets/img/avatar/user_profile.svg"; // Default image
    $emp_number = ""; // Default emp_number
    $employee_phone = "";
    $employee_address = "";
    $join_date = "";
}

// Close the statement
$stmt->close();

// Set the alertType, alertTitle, and alertMessage based on the result
$alertType = '';
$alertTitle = '';
$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['loginPassword'];
    $new_password = $_POST['newPassword'];
    $confirm_password = $_POST['confirmPassword'];

    // Menggunakan prepared statement untuk query
    $stmt = $conn->prepare("SELECT password_employee FROM tbl_emp_7tt8 WHERE username_employee = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result(); // Store the result
    $stmt->bind_result($password_employee);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($old_password, $password_employee)) {
        if ($new_password === $confirm_password) {
            // Validasi kata sandi baru
            $uppercase = preg_match('@[A-Z]@', $new_password);
            $lowercase = preg_match('@[a-z]@', $new_password);
            $number = preg_match('@[0-9]@', $new_password);
            $specialChars = preg_match('@[^\w]@', $new_password);

            if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($new_password) < 8) {
                $alertType = 'error';
                $alertTitle = 'Kata Sandi Lemah';
                $alertMessage = 'Kata sandi baru harus memiliki setidaknya 8 karakter, termasuk huruf kecil, huruf besar, angka, dan simbol khusus.';
            } else {
                // Hash kata sandi baru
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                // Menggunakan prepared statement untuk update
                $stmt = $conn->prepare("UPDATE tbl_emp_7tt8 SET password_employee = ? WHERE username_employee = ?");
                $stmt->bind_param("ss", $new_password_hashed, $username);
                if ($stmt->execute()) {
                    $alertType = 'success';
                    $alertTitle = 'Kata Sandi Diperbarui';
                    $alertMessage = 'Kata sandi Anda telah berhasil diperbarui.';
                } else {
                    $alertType = 'error';
                    $alertTitle = 'Error';
                    $alertMessage = 'Terjadi kesalahan saat memperbarui kata sandi. Silakan coba lagi.';
                }
                $stmt->close();
            }
        } else {
            $alertType = 'error';
            $alertTitle = 'Konfirmasi Gagal';
            $alertMessage = 'Kata sandi baru dan konfirmasi kata sandi tidak cocok.';
        }
    } else {
        $alertType = 'error';
        $alertTitle = 'Kata Sandi Lama Salah';
        $alertMessage = 'Kata sandi lama yang Anda masukkan salah.';
    }
}

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

?>
<body class="geex-dashboard">

<?php include './partials/header.php'; ?>

<main class="geex-main-content">
    <?php include './partials/sidebar.php'; ?>
    <?php include './partials/customizer.php'; ?>

    <div class="geex-content">
        <div class="geex-content__header">
        <div class="geex-content__header__content">
                   
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
            <div class="row">
                <div class="col-md-9 d-flex flex-column justify-content-center mb-30">
                    <h2 class="geex-content__header__title">Halaman Atur Ulang Kata Sandi</h2>
                    <p class="geex-content__header__subtitle">Selamat datang di halaman atur ulang kata sandi PT Maulana Raya Abadi. Keamanan akun Anda adalah prioritas utama kami, oleh karena itu, kami menyarankan Anda untuk selalu menggunakan kata sandi yang kuat dan unik. Pada halaman ini, Anda dapat dengan mudah mengganti kata sandi akun Anda untuk menjaga privasi dan keamanan data pribadi.</p>
                    <p class="geex-content__header__subtitle mt-20"><strong>Pentingnya Menggunakan Kata Sandi yang Kuat</strong></p>
                    <p class="geex-content__header__subtitle mt-20">
                        Untuk keamanan akun Anda, kata sandi harus memiliki minimal <strong>8 karakter</strong>. Gunakan kombinasi <strong>huruf kecil (a-z)</strong>, <strong> angka (0-9) </strong>, dan simbol khusus seperti <strong>(!, #, $, dll.)</strong>. Hindari menggunakan informasi pribadi yang mudah ditebak, seperti nama Anda, nama anggota keluarga, atau kota tempat tinggal. Disarankan untuk mengganti kata sandi Anda secara berkala guna menjaga keamanan akun.
                    </p>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="geex-content__authentication__form-group">
                        <div class="geex-content__authentication__label-wrapper">
                            <label for="loginPassword">Kata Sandi Lama</label>
                        </div>
                        <input type="password" id="loginPassword" name="loginPassword" placeholder="Masukan Kata Sandi Lama" required>
                        <i class="uil-eye toggle-password-type"></i>
                    </div>
                    <div class="geex-content__authentication__form-group">
                        <div class="geex-content__authentication__label-wrapper">
                            <label for="newPassword">Kata Sandi Baru</label>
                        </div>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Masukan Kata Sandi Baru" required>
                        <i class="uil-eye toggle-password-type"></i>
                    </div>
                    <div class="geex-content__authentication__form-group">
                        <div class="geex-content__authentication__label-wrapper">
                            <label for="confirmPassword">Konfirmasi Kata Sandi</label>
                        </div>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi Kata Sandi Baru" required>
                        <i class="uil-eye toggle-password-type"></i>
                    </div>
                    <div class="geex-content__form__single d-flex gap-10">
                        <button type="submit" class="geex-btn geex-btn--primary">Atur Ulang Kata Sandi</button>
                    </div>
                </form>
            </div>
    </div>
    

</main>


         

<!-- inject:js-->

<!-- inject:js-->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    <?php if (!empty($alertType)) { ?>
        Swal.fire({
            icon: '<?php echo $alertType; ?>',
            title: '<?php echo $alertTitle; ?>',
            text: '<?php echo $alertMessage; ?>',
            <?php if ($alertType === 'success') { ?>
                showConfirmButton: false,
                timer: 1500
            <?php } ?>
        }).then(function() {
            <?php if ($alertType === 'success') { ?>
                window.location = "index.php"; // Redirect to index.php on success
            <?php } ?>
        });
    <?php } ?>
});
</script>
<script src="assets/js/autoLogout.js"></script>
<script src="assets/js/noInternetcon.js"></script>

</script>
<?php include './partials/script.php'; ?>
<!-- endinject-->
</body>

</html>
