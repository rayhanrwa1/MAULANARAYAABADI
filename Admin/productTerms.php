<!doctype html>
<html lang="en" dir="ltr">

<?php
$title = 'index'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if user is logged in
if (!isset($_SESSION['username_employee'])) {
    header('Location: signin.php');
    exit();
}

include './partials/head.php';
include 'connection.php';

$editMode = false; // Flag to check if in edit mode
$jobData = []; // Array to store job data if editing

// Check if we're in edit mode
if (isset($_GET['edit'])) {
    $editMode = true;
    $jobId = $_GET['edit'];

    // Fetch job data based on the id
    $stmt = $conn->prepare("SELECT posisi, deskripsi, kouta, kategori, link_pendaftaran, status FROM tbl_karier WHERE id = ?");
    $stmt->bind_param('i', $jobId);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobData = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posisi = $_POST['posisi'];
    $deskripsi = $_POST['deskripsi'];
    $kouta = $_POST['kouta'];
    $kategori = $_POST['kategori'];
    $link_pendaftaran = $_POST['link_pendaftaran'];
    $status = $_POST['status'];

    // Validate form data
    if (!empty($posisi) && !empty($deskripsi) && !empty($status)) {
        if ($editMode) {
            // Update existing job posting
            $stmt = $conn->prepare("UPDATE tbl_karier SET posisi = ?, deskripsi = ?, kouta = ?, kategori = ?, link_pendaftaran = ?, status = ? WHERE id = ?");
            $stmt->bind_param('ssisssi', $posisi, $deskripsi, $kouta, $kategori, $link_pendaftaran, $status, $jobId);
        } else {
            // Insert new job posting
            $stmt = $conn->prepare("INSERT INTO tbl_karier (posisi, deskripsi, kouta, kategori, link_pendaftaran, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssisss', $posisi, $deskripsi, $kouta, $kategori, $link_pendaftaran, $status);
        }

        // Execute query
        if ($stmt->execute()) {
            $success = true;
            header("Location: recruitment.php?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Pastikan semua data yang wajib diisi sudah diisi.";
    }
}

// Default form values
$posisi = $editMode && isset($jobData['posisi']) ? htmlspecialchars($jobData['posisi']) : '';
$deskripsi = $editMode && isset($jobData['deskripsi']) ? htmlspecialchars($jobData['deskripsi']) : '';
$kouta = $editMode && isset($jobData['kouta']) ? htmlspecialchars($jobData['kouta']) : '';
$kategori = $editMode && isset($jobData['kategori']) ? htmlspecialchars($jobData['kategori']) : '';
$link_pendaftaran = $editMode && isset($jobData['link_pendaftaran']) ? htmlspecialchars($jobData['link_pendaftaran']) : '';
$status = $editMode && isset($jobData['status']) ? htmlspecialchars($jobData['status']) : '';


// Tampilkan halaman seperti biasa
$username = $_SESSION['username_employee'];
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
    
<?php include './partials/header.php'?>

<main class="geex-main-content">
        
<?php include './partials/sidebar.php'?>
    
<?php include './partials/customizer.php'?>

<div class="geex-content">
        <div class="geex-content__header">
            <div class="geex-content__header__content">
                <h2 class="geex-content__header__title">Beranda Pengguna</h2>
                <p class="geex-content__header__subtitle">Halaman Beranda Untuk Pengguna Produk PT Maulana Raya Abadi</p>
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
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="geex-content__form__single__box mt-2">
                        <input type="text" name="posisi" placeholder="Posisi yang dibuka" class="form-control" value="<?php echo $posisi; ?>" required/>
                        <input type="text" name="kategori" placeholder="Kategori (Contoh : Sales Representative) " class="form-control" value="<?php echo $kategori; ?>"/>
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <input type="text" name="status" placeholder="Status Pekerjaan (Contoh : Magang)" class="form-control" value="<?php echo $status; ?>" required />
                        <input type="number" name="kouta" placeholder="Kouta Pekerjaan" class="form-control" value="<?php echo $kouta; ?>" />
                        <input type="text" name="link_pendaftaran" placeholder="Link Recruitment (Google Form.dll)" class="form-control" value="<?php echo $link_pendaftaran; ?>" />
                    </div>
                    <div class="geex-content__form__single__box mt-2">
                        <label for="deskripsi" class="input-label">Deskripsi Persyaratan</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Silakan mengisi persyaratan di bawah ini dalam bentuk paragraf." style="border-radius: 5px;"><?php echo $deskripsi; ?></textarea>
                    </div>
                    <div class="geex-content__form__single d-flex gap-10">
                        <button type="submit" class="geex-btn geex-btn--primary"><?php echo $editMode ? 'Update' : 'Submit'; ?></button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

</main>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var product_id = this.getAttribute('data-id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#246c3c',
                cancelButtonColor: '#bbc125',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to deleteProduct.php with product_id
                    window.location.href = "deleteProduct.php?delete=" + product_id;
                }
            });
        });
    });

    // Check if success message is set
    <?php if (isset($success) && $success): ?>
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Data Berhasil Diperbarui',
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location = 'recruitment.php';
    });
    <?php endif; ?>
</script>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Data Berhasil Diperbarui',
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location = 'recruitmentView.php'; // Redirect to clear the URL
        });
    </script>
<?php endif; ?>

<!-- inject:js-->
<?php include './partials/script.php'?>
<!-- endinject-->
</body>
</html>



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
