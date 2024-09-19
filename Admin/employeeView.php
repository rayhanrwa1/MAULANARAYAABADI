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
    header('Location: signin.php');
    exit();
}

include './partials/head.php';
include 'connection.php'; // Include the database connection
include 'check_admin.php'; 

// Fetch user data securely using prepared statements
$username = $_SESSION['username_employee'];
$stmt = $conn->prepare("SELECT employee_name, emp_number, position_name, employee_phone, join_date, photo FROM tbl_emp_7tt8 WHERE username_employee = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($employee_name, $emp_number, $position_name, $employee_phone, $join_date, $photo);

if ($stmt->fetch()) {
    $employee_name = htmlspecialchars($employee_name);
    $emp_number = htmlspecialchars($emp_number);
    $position_name = htmlspecialchars($position_name);
    $employee_phone = htmlspecialchars($employee_phone);
    $join_date = htmlspecialchars($join_date);
    $photo = !empty($photo) ? htmlspecialchars($photo) : "assets/img/avatar/user_profile.svg";
} else {
    $employee_name = "Unknown User";
    $emp_number = "Unknown NIP";
    $position_name = "Unknown Position";
    $employee_phone = "Unknown Phone";
    $join_date = "Unknown Join Date";
    $photo = "assets/img/avatar/user_profile.svg";
}
$stmt->close();

// Fetch all user data
$sql = "SELECT employee_name, emp_number, position_name, employee_phone, join_date, photo FROM tbl_emp_7tt8";
$result = $conn->query($sql);

$employees = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_employee = $_POST['username_employee'];
    $employee_name = $_POST['employee_name'];
    $position_name = $_POST['position_name'];
    $password_employee = password_hash("MRAdef4ult", PASSWORD_DEFAULT);
    $employee_phone = $_POST['employee_phone'];
    $join_date = $_POST['join_date'];
    $emp_number = $_POST['emp_number'];
    $employee_address = $_POST['employee_address'];

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tbl_emp_7tt8 WHERE username_employee = ?");
    $stmt->bind_param('s', $username_employee);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();    

    if ($count > 0) {
        $_SESSION['status'] = 'username_exists';
    } else {
        // Proses upload foto jika ada
        $photo = "";
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "./assets/image_db/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Cek apakah file benar-benar gambar
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if ($check !== false) {
                // Pindahkan file ke target directory
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                    $photo = $target_file; // Simpan path ke database
                } else {
                    $_SESSION['status'] = 'upload_failed';
                }
            } else {
                $_SESSION['status'] = 'file_not_image';
            }
        }

        // Simpan data ke database
        $stmt = $conn->prepare("INSERT INTO tbl_emp_7tt8 (username_employee, employee_name, position_name, photo, password_employee, employee_phone, join_date, emp_number, employee_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $username_employee, $employee_name, $position_name, $photo, $password_employee, $employee_phone, $join_date, $emp_number, $employee_address);
        if ($stmt->execute()) {
            $_SESSION['status'] = 'success';
        } else {
            $_SESSION['status'] = 'db_error';
        }
        $stmt->close();
    }

    $conn->close();
    header('Location: employeeView.php');
    exit();
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
    $notifications = [];
    while ($row = $notificationsResult->fetch_assoc()) {
        $notifications[] = $row;
    }
}
$conn->close();
?>



<body class="geex-dashboard">

<?php include './partials/header.php'; ?>

<main class="geex-main-content">


    <?php include './partials/sidebar.php'; ?>
    <?php include './partials/customizer.php'; ?>
    
    <div class="geex-content">
        <div class="geex-content__header">
            <div class="geex-content__header__content">
                <h2 class="geex-content__header__title">Halaman Review</h2>
                <p class="geex-content__header__subtitle">Halaman Review Pegawai PT Maulana Raya Abadi</p>
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
        <div class="geex-content__todo__header__title pb-20">
            <button class="geex-btn geex-btn--primary geex-btn__add-modal"><i class="uil-plus"></i>Pegawai Baru</button>
        </div>
        <div class="tab-pane fade show active" id="todo_grid_content" role="tabpanel" aria-labelledby="todo_grid_tab">
            <div class="row g-40">
                <?php foreach ($employees as $employee): ?>
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="single-contact-grid-area">
                        <div class="top-area">
                            <div class="avatar">
                                <img src="<?php echo !empty($employee['photo']) ? $employee['photo'] : 'assets/img/avatar/user.svg'; ?>" alt="user">
                            </div>
                            <div class="action-area">
                                <div class="geex-content__chat__header__filter__btn">
                                    <i class="uil uil-ellipsis-v"></i>
                                </div>
                                <div class="geex-content__chat__header__filter__content">
                                    <a class="dropdown-item delete-btn" data-id="<?php echo $employee['emp_number']; ?>" href="#">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class="details-area">
                            <h4 class="name"><?php echo $employee['employee_name']; ?></h4>
                            <span class="designation"><?php echo $employee['emp_number']; ?></span>
                            <div class="contact-wrapper">
                                <a class="single-contact">
                                    <i class="uil uil-bag"></i>
                                    <p><?php echo $employee['position_name']; ?></p>
                                </a>
                                <a class="single-contact">
                                    <i class="uil uil-phone"></i>
                                    <p><?php echo $employee['employee_phone']; ?></p>
                                </a>
                                <a class="single-contact">
                                    <i class="uil uil-calendar-alt"></i>
                                    <p><?php echo $employee['join_date']; ?></p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="geex-content__modal__form">
						<div class="geex-content__modal__form__header">
							<h3 class="geex-content__modal__form__title">Daftar Pegawai Baru</h3>
							<button class="geex-content__modal__form__close">
								<i class="uil-times"></i>
							</button>
						</div>
						<form class="geex-content__modal__form__wrapper" method="POST" enctype="multipart/form-data">
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="username_employee" class="geex-content__modal__form__input" placeholder="Username" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="employee_name" class="geex-content__modal__form__input" placeholder="Nama Pegawai" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="position_name" class="geex-content__modal__form__input" placeholder="Posisi" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="employee_phone" class="geex-content__modal__form__input" placeholder="Nomor Telepon" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="date" name="join_date" class="geex-content__modal__form__input" placeholder="Tanggal Bergabung" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="emp_number" class="geex-content__modal__form__input" placeholder="Nomor Pegawai" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                                <input type="text" name="employee_address" class="geex-content__modal__form__input" placeholder="Alamat" required />
                            </div>
                            <div class="geex-content__modal__form__item">
                            <p class="upload-instructions mb-10 mt-10">Foto harus berukuran 1080 x 1080 piksel atau rasio 1:1.</p>
                                <label for="photo_product" class="upload-label">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <path d="M20.382 13.1751C20.2706 13.2827 20.1817 13.4115 20.1206 13.5538C20.0594 13.6962 20.0272 13.8492 20.0259 14.0042C20.0245 14.1591 20.0541 14.3127 20.1127 14.4561C20.1714 14.5995 20.258 14.7297 20.3675 14.8393C20.4771 14.9488 20.6073 15.0354 20.7507 15.0941C20.8941 15.1527 21.0477 15.1823 21.2026 15.1809C21.3575 15.1796 21.5106 15.1474 21.653 15.0862C21.7953 15.0251 21.9241 14.9362 22.0317 14.8248L23.833 13.0235C24.9853 11.8445 25.6262 10.2587 25.6167 8.61024C25.6073 6.96173 24.9482 5.38343 23.7826 4.21775C22.6169 3.05206 21.0386 2.393 19.3901 2.38356C17.7416 2.37411 16.1558 3.01504 14.9768 4.16729L13.1755 5.96863C13.0641 6.07625 12.9752 6.20498 12.9141 6.34732C12.8529 6.48966 12.8207 6.64275 12.8194 6.79766C12.818 6.95257 12.8476 7.10619 12.9062 7.24957C12.9649 7.39295 13.0515 7.52321 13.161 7.63275C13.2706 7.7423 13.4008 7.82892 13.5442 7.88759C13.6876 7.94625 13.8412 7.97577 13.9961 7.97442C14.151 7.97307 14.3041 7.94089 14.4465 7.87975C14.5888 7.8186 14.7176 7.72972 14.8252 7.61829L16.6265 5.81696C16.9906 5.44881 17.424 5.15627 17.9015 4.95615C18.3791 4.75603 18.8916 4.65227 19.4094 4.65086C19.9272 4.64944 20.4402 4.75039 20.9188 4.94789C21.3975 5.1454 21.8324 5.43557 22.1986 5.80172C22.5647 6.16787 22.8549 6.60278 23.0524 7.08145C23.2499 7.56012 23.3509 8.07311 23.3494 8.59093C23.348 9.10874 23.2443 9.62117 23.0442 10.0987C22.844 10.5763 22.5515 11.0097 22.1833 11.3738L20.382 13.1751Z" fill="#A3A3A3"></path>
                                    <path d="M7.61863 14.8248C7.73006 14.7172 7.81894 14.5884 7.88008 14.4461C7.94123 14.3037 7.97341 14.1507 7.97476 13.9957C7.9761 13.8408 7.94658 13.6872 7.88792 13.5438C7.82926 13.4005 7.74263 13.2702 7.63309 13.1606C7.52355 13.0511 7.39329 12.9645 7.24991 12.9058C7.10653 12.8472 6.9529 12.8176 6.798 12.819C6.64309 12.8203 6.49 12.8525 6.34766 12.9137C6.20532 12.9748 6.07658 13.0637 5.96896 13.1751L4.16763 14.9764C3.01537 16.1554 2.37445 17.7412 2.38389 19.3897C2.39334 21.0382 3.05239 22.6165 4.21808 23.7822C5.38377 24.9478 6.96207 25.6069 8.61058 25.6163C10.2591 25.6258 11.8448 24.9849 13.0238 23.8326L14.8251 22.0313C15.0376 21.8112 15.1552 21.5165 15.1526 21.2106C15.1499 20.9047 15.0272 20.6121 14.8109 20.3958C14.5946 20.1795 14.302 20.0568 13.9961 20.0542C13.6902 20.0515 13.3955 20.1691 13.1755 20.3816L11.3741 22.1829C10.6359 22.9132 9.63856 23.3215 8.60017 23.3186C7.56177 23.3158 6.56672 22.902 5.83246 22.1678C5.0982 21.4335 4.68443 20.4385 4.68159 19.4001C4.67875 18.3617 5.08706 17.3644 5.8173 16.6261L7.61863 14.8248Z" fill="#A3A3A3"></path>
                                    <path d="M9.57303 18.4334C9.79181 18.6521 10.0885 18.775 10.3979 18.775C10.7072 18.775 11.0039 18.6521 11.2227 18.4334L18.4339 11.2222C18.5453 11.1146 18.6342 10.9859 18.6953 10.8435C18.7565 10.7012 18.7886 10.5481 18.79 10.3932C18.7913 10.2383 18.7618 10.0847 18.7031 9.94129C18.6445 9.79791 18.5579 9.66765 18.4483 9.55811C18.3388 9.44857 18.2085 9.36194 18.0651 9.30328C17.9218 9.24462 17.7681 9.2151 17.6132 9.21644C17.4583 9.21779 17.3052 9.24997 17.1629 9.31112C17.0205 9.37226 16.8918 9.46114 16.7842 9.57257L9.57303 16.7779C9.46385 16.8863 9.37721 17.0153 9.31808 17.1574C9.25895 17.2994 9.22852 17.4518 9.22852 17.6057C9.22852 17.7595 9.25895 17.9119 9.31808 18.0539C9.37721 18.196 9.46385 18.325 9.57303 18.4334Z" fill="#A3A3A3"></path>
                                </svg>
                                    <span class="upload-text">Unggah Foto</span>
                                </label>
                                <input type="file" class="geex-content__modal__form__input" name="photo" id="photo_product" style="display: none;">
                            </div>
                            <div class="geex-content__modal__form__item pb-80  d-flex gap-10">
                                <button type="submit" class="geex-content__modal__form__submit">Submit</button>
                            </div>
                        </form>
					</div>
        </div>
    </div>
</main>

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="assets/js/autoLogout.js"></script>
<script src="assets/js/noInternetcon.js"></script>
<script src="assets/js/check_admin.js"></script>
<script>
    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            var emp_number = this.getAttribute('data-id'); // Get the employee ID from the data-id attribute

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#246c3c',
                cancelButtonColor: '#bbc125',
                confirmButtonText: 'Ya, hapus!',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to a PHP script that handles deletion
                    window.location.href = "delete_user.php?id=" + emp_number;
                }
            });
        });
    });
</script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['status'])): ?>
                switch ("<?php echo $_SESSION['status']; ?>") {
                    case 'success':
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data Pegawai berhasil ditambahkan.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#246c3c',
                        }).then(() => {
                            window.location.reload();
                        });
                        break;
                    case 'username_exists':
                        Swal.fire({
                            title: 'Username sudah ada!',
                            text: 'Silakan gunakan username yang lain.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        break;
                    case 'upload_failed':
                        Swal.fire({
                            title: 'Gagal Mengupload Foto!',
                            text: 'Terjadi kesalahan saat mengupload foto.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        break;
                    case 'file_not_image':
                        Swal.fire({
                            title: 'File bukan gambar!',
                            text: 'Silakan upload file gambar yang valid.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        break;
                    case 'db_error':
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        break;
                }
                <?php unset($_SESSION['status']); ?>
            <?php endif; ?>
        });
    </script>
    <script>
        // Mengambil nilai is_admin dari PHP
        var isAdmin = <?php echo json_encode($is_admin); ?>;

        // Panggil fungsi dari file .js
        checkAdminAccess(isAdmin);
    </script>


<?php include './partials/script.php'; ?>
<!-- endinject-->

</body>
</html>
