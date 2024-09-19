<!doctype html>
<html lang="en" dir="ltr">
<?php
$title = 'index';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Check if user is logged in
if (!isset($_SESSION['username_employee'])) {
    // If not logged in, redirect to login page
    header('Location: signin.php');
    exit();
}

include './partials/head.php';
include 'connection.php'; // Include the database connection
include 'check_admin.php'; 

$username = $_SESSION['username_employee'];

// Fetch the employee number of the logged-in user
$logged_in_emp_number = ''; // Initialize the variable
$stmt = $conn->prepare("SELECT emp_number FROM tbl_emp_7tt8 WHERE username_employee = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($logged_in_emp_number);
$stmt->fetch();
$stmt->close();

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

// Fetch data from tbl_access_guard55
$reviews = [];
$stmt = $conn->prepare("SELECT emp_number, employee_name, employee_phone, photo FROM tbl_access_guard55");
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($emp_number, $employee_name, $employee_phone, $photo);

while ($stmt->fetch()) {
    $reviews[] = [
        'emp_number' => $emp_number,
        'employee_name' => $employee_name,
        'employee_phone' => $employee_phone,
        'photo' => $photo,
    ];
}
$stmt->close();

// Handle accept action
if (isset($_GET['accept']) && !empty($_GET['emp_number'])) {
    $emp_number = $_GET['emp_number'];
    $password = 'MRAdef4ult';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update hashed password in tbl_emp_7tt8
    $stmt_update = $conn->prepare("UPDATE tbl_emp_7tt8 SET password_employee = ? WHERE emp_number = ?");
    $stmt_update->bind_param('ss', $hashed_password, $emp_number);
    
    if ($stmt_update->execute()) {
        // Remove entry from tbl_access_guard55
        $stmt_delete = $conn->prepare("DELETE FROM tbl_access_guard55 WHERE emp_number = ?");
        $stmt_delete->bind_param('s', $emp_number);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Check if the logged-in user is the one being updated
        if ($logged_in_emp_number == $emp_number) {
            // If so, log them out
            session_destroy();
            header('Location: signin.php');
            exit();
        } else {
            // Set success flag for SweetAlert
            $success = true;

            // Remove the accepted review from the $reviews array
            $reviews = array_filter($reviews, function($review) use ($emp_number) {
                return $review['emp_number'] !== $emp_number;
            });
        }
    }
    $stmt_update->close();
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

<?php include './partials/head.php' ?>

<body class="geex-dashboard"> 
	
<?php include './partials/header.php'?>

<main class="geex-main-content">	

<?php include './partials/sidebar.php'?>	

<?php include './partials/customizer.php'?>

<div class="geex-content">
    <div class="geex-content__header">
        <div class="geex-content__header__content">
            <h2 class="geex-content__header__title">Pemulihan Password</h2>
            <p class="geex-content__header__subtitle">Halaman Pemulihan Kata Sandi PT Maulana Raya Abadi</p>
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

    <div class="geex-content__wrapper">
        <div class="geex-content__section-wrapper">
            <div class="geex-content__section geex-content__section--transparent geex-content__review">
                <div class="geex-content__section__header">
                    <div class="geex-content__section__header__content-part">
                        <div class="geex-content__section__header__btn geex-content__section__header__swiper-btn">
                            <div class="swiper-btn swiper-btn-prev">
                                <i class="uil-arrow-left"></i>
                            </div>
                            <div class="swiper-btn swiper-btn-next">
                                <i class="uil-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="geex-content__section__content">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <?php foreach ($reviews as $review): ?>
                            <div class="swiper-slide">
                                <div class="geex-content__review__single">
                                    <div class="geex-content__review__single__header">
                                        <div class="geex-content__review__single__header__img">
                                            <img src="<?php echo htmlspecialchars($review['photo']); ?>" alt="User" />
                                        </div>
                                        <div class="geex-content__review__single__header__text">
                                            <h4 class="geex-content__review__single__header__title"><?php echo htmlspecialchars($review['employee_name']); ?></h4>
                                            <p class="geex-content__review__single__header__subtitle"><?php echo htmlspecialchars($review['emp_number']); ?></p>
                                        </div>
                                    </div>
                                    <div class="geex-content__review__single__bottom">
                                        <a href="#" data-id="<?php echo urlencode($review['emp_number']); ?>" class="geex-content__review__single__btn success-color accept-btn">Accept</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<!-- inject:js-->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/autoLogout.js"></script>
<script src="assets/js/noInternetcon.js"></script>
<script src="assets/js/check_admin.js"></script>

<script>
    // Initialize Swiper
    var swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-btn-next',
            prevEl: '.swiper-btn-prev',
        },
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.accept-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                var emp_number = button.getAttribute('data-id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Tindakan ini akan memperbarui password karyawan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#246c3c',  
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?accept=true&emp_number=' + encodeURIComponent(emp_number);
                    }
                });
            });
        });
    });

    <?php if (isset($success) && $success): ?>
    Swal.fire({
        title: 'Berhasil!',
        text: 'Password telah diperbarui.',
        icon: 'success',
        confirmButtonText: 'Ok',
        confirmButtonColor: '#246c3c'
    });
    <?php endif; ?>
</script>
<script>
    // Mengambil nilai is_admin dari PHP
    var isAdmin = <?php echo json_encode($is_admin); ?>;

    // Panggil fungsi dari file .js
    checkAdminAccess(isAdmin);
</script>



<!-- inject:js-->
<?php include './partials/script.php'?>
<!-- endinject-->
</body>
</html>
