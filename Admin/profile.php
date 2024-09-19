<!doctype html>
<html lang="en" dir="ltr">

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$title = 'Profile';

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
$sql = "SELECT employee_name, position_name, photo, emp_number, employee_phone, employee_address, join_date FROM tbl_emp_7tt8 WHERE username_employee = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $employee_name = $user['employee_name'];
    $position_name = $user['position_name'];
    $photo = $user['photo'] ? $user['photo'] : "assets/img/avatar/user_profile.svg"; // Use default image if no photo is found
    $emp_number = $user['emp_number'];
    $employee_phone = $user['employee_phone'];
    $employee_address = $user['employee_address'];
    $join_date = $user['join_date'];
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses data form untuk pembaruan
    $new_employee_name = $_POST['employee_name'];
    $new_position_name = $_POST['position_name'];
    $new_employee_phone = $_POST['employee_phone'];
    $new_employee_address = $_POST['employee_address'];
    $new_join_date = $_POST['join_date'];

    // Cek apakah emp_number kosong sebelumnya
    if (empty($emp_number) && !empty($_POST['emp_number'])) {
        $emp_number = $_POST['emp_number'];
    }

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "assets/image_db/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file benar-benar gambar
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            // Pindahkan file yang diupload ke direktori tujuan
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                $photo = $target_file; // Update jalur foto
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    // Update database
    $update_sql = "UPDATE tbl_emp_7tt8 SET 
                    employee_name='$new_employee_name', 
                    position_name='$new_position_name', 
                    employee_phone='$new_employee_phone',
                    employee_address='$new_employee_address',
                    join_date='$new_join_date',
                    photo='$photo', 
                    emp_number='$emp_number' 
                  WHERE username_employee='$username'";

    if ($conn->query($update_sql) === TRUE) {
        // Mengatur variabel untuk menampilkan SweetAlert
        $success = true;
    } else {
        echo "Error updating record: " . $conn->error;
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
                <h2 class="geex-content__header__title"><?php echo  $emp_number; ?></h2>
                <p class="geex-content__header__subtitle"><?php echo $username; ?></p>
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
        <div class="container">
            <div class="row">
                <div class="col-md-3 d-flex align-items-center">
                    <img class="img-fluid" height="120" width="120" src="<?php echo $photo; ?>" alt="user" style="border-radius: 10px;" />
                </div>
                <div class="col-md-9 d-flex flex-column justify-content-center">
                    <h4 class="geex-content__chat__header__title"><?php echo $employee_name; ?></h4>
                    <span class="designation"><?php echo $position_name; ?></span>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-7" class="input-label">Username</label>
                        <input type="text" placeholder="Username" class="form-control" value="<?php echo $username; ?>" readonly />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-7" class="input-label">Nama</label>
                        <input type="text" name="employee_name" placeholder="Nama" class="form-control" value="<?php echo $employee_name; ?>" required />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-2" class="input-label">Nomor Identifikasi Karyawan</label>
                        <input type="text" name="emp_number" placeholder="Nomor Identifikasi Karyawan" class="form-control" value="<?php echo $emp_number; ?>" <?php echo !empty($emp_number) ? 'readonly' : ''; ?> required />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-2" class="input-label">Jabatan</label>
                        <input type="text" name="position_name" placeholder="Jabatan" class="form-control" value="<?php echo $position_name; ?>" required />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-2" class="input-label">Tanggal Bergabung</label>
                        <input type="date" name="join_date" placeholder="Tanggal Bergabung" class="form-control" value="<?php echo $join_date; ?>" required />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-2" class="input-label">Nomor Telepon</label>
                        <input type="text" name="employee_phone" placeholder="Nomor telepon" class="form-control" value="<?php echo $employee_phone; ?>" required />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="geex-input-2" class="input-label">Alamat</label>
                        <input type="text" name="employee_address" placeholder="Alamat" class="form-control" value="<?php echo $employee_address; ?>" required />
                    </div>
                    <div class="geex-content__form__single__box mb-20 mt-2 d-flex">
                        <div class="flex-grow-1 me-2">
                            <label for="photo" class="upload-label">
                                <!-- SVG dikomentari karena panjang -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                                    <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                                    <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2996 9.22891 17.4519 9.22987 17.6068C9.23082 17.7618 9.26277 17.9146 9.32344 18.0584C9.38412 18.2021 9.47224 18.3331 9.5834 18.4425L9.57303 18.4334Z" fill="#A3A3A3"></path>
                                </svg>
                                Foto Profil | Ket : Foto harus 1:1 (1080x1080)
                            </label>
                        </div>
                        <input type="file" name="photo" id="photo" class="form-control" style="border-radius: 5px; display: none;">
                    </div>
                    <div class="geex-content__form__single d-flex gap-10">
                        <button type="submit" class="geex-btn geex-btn--primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>

<!-- inject:js-->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.27.0/dist/apexcharts.min.js"></script>
<script src="assets/js/autoLogout.js"></script>
<script src="assets/js/noInternetcon.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (isset($success)) { ?>
    <script>
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Data berhasil diperbarui!",
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location = "profile.php"; // Redirect ke halaman profile.php setelah SweetAlert
        });
    </script>
<?php } ?>

<?php include './partials/script.php'; ?>
<!-- endinject-->
</body>

</html>
