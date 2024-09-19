<!doctype html>
<html lang="en" dir="ltr">

<?php
// Inisialisasi title dan pengaturan untuk debugging
$title = 'index'; 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username_employee'])) {
    header('Location: signin.php');
    exit();
}

// Termasuk partial untuk head dan koneksi ke database
include './partials/head.php';
include 'connection.php';

// Proses ketika form disubmit (method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDirectory = "assets/image_db/userPage/";

    // Ambil data dari form
    $poster_1 = $_FILES['poster_1']['name'];
    $poster_2 = $_FILES['poster_2']['name'];
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;

    if ($id) {
        // Ambil data poster lama untuk menghapus file jika diperlukan
        $stmt = $conn->prepare("SELECT poster_1, poster_2 FROM poster_tbl WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $oldPosters = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Hapus file lama jika ada file baru
        if ($poster_1 && $oldPosters['poster_1'] && file_exists($uploadDirectory . $oldPosters['poster_1'])) {
            unlink($uploadDirectory . $oldPosters['poster_1']);
        }
        if ($poster_2 && $oldPosters['poster_2'] && file_exists($uploadDirectory . $oldPosters['poster_2'])) {
            unlink($uploadDirectory . $oldPosters['poster_2']);
        }

        // Pindahkan file yang diupload ke direktori target
        if ($poster_1) {
            move_uploaded_file($_FILES['poster_1']['tmp_name'], $uploadDirectory . basename($poster_1));
        } else {
            $poster_1 = $oldPosters['poster_1']; // Gunakan file lama jika tidak ada file baru
        }
        
        if ($poster_2) {
            move_uploaded_file($_FILES['poster_2']['tmp_name'], $uploadDirectory . basename($poster_2));
        } else {
            $poster_2 = $oldPosters['poster_2']; // Gunakan file lama jika tidak ada file baru
        }

        // Update data di database
        $stmt = $conn->prepare("UPDATE poster_tbl SET poster_1 = ?, poster_2 = ? WHERE id = ?");
        $stmt->bind_param("ssi", $poster_1, $poster_2, $id);
    } else {
        // Masukkan data baru ke database
        $stmt = $conn->prepare("INSERT INTO poster_tbl (poster_1, poster_2) VALUES (?, ?)");
        $stmt->bind_param("ss", $poster_1, $poster_2);

        // Pindahkan file yang diupload ke direktori target
        move_uploaded_file($_FILES['poster_1']['tmp_name'], $uploadDirectory . basename($poster_1));
        move_uploaded_file($_FILES['poster_2']['tmp_name'], $uploadDirectory . basename($poster_2));
    }

    if ($stmt->execute()) {
        $success = true;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Proses pengambilan data untuk edit
$editData = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM poster_tbl WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Mengambil data user berdasarkan session
$username = $_SESSION['username_employee'];
$stmt = $conn->prepare("SELECT employee_name, position_name, photo FROM tbl_emp_7tt8 WHERE username_employee = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($employee_name, $position_name, $photo);
$stmt->fetch();
$stmt->close();

// Jika data tidak ditemukan, gunakan nilai default
if (empty($employee_name)) {
    $employee_name = "Unknown User";
    $position_name = "Unknown Position";
    $photo = "assets/img/avatar/user_profile.svg";
} else {
    $employee_name = htmlspecialchars($employee_name);
    $position_name = htmlspecialchars($position_name);
    $photo = !empty($photo) ? htmlspecialchars($photo) : "assets/img/avatar/user_profile.svg";
}

// SQL query untuk menampilkan notifikasi reset password
$notificationsQuery = "
    SELECT a.employee_name, a.emp_number, a.photo, b.forgot_password_request_at
    FROM tbl_access_guard55 AS b
    JOIN tbl_emp_7tt8 AS a ON a.emp_number = b.emp_number
    WHERE b.forgot_password_request_at IS NOT NULL
    ORDER BY b.forgot_password_request_at DESC
    LIMIT 5";

// Jalankan query
$notificationsResult = $conn->query($notificationsQuery);

// Cek apakah eksekusi query berhasil
if ($notificationsResult === FALSE) {
    echo "Error: " . $conn->error;
    $notifications = [];
} else {
    // Ambil semua baris hasil query
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
                <div class="geex-content__form__single__box mb-5">
                    <label for="poster_1" class="input-label">Unggah Poster 650 x 295 (1)</label>
                    <input type="file" class="form-control" name="poster_1" id="poster_1">
                </div>
                <div class="geex-content__form__single__box mb-5">
                    <label for="poster_2" class="input-label">Unggah Poster 650 x 295 (2)</label>
                    <input type="file" class="form-control" name="poster_2" id="poster_2">
                </div>
                <input type="hidden" name="id" value="<?php echo isset($editData['id']) ? htmlspecialchars($editData['id']) : ''; ?>">
                <div class="geex-content__form__single d-flex gap-10">
                    <button type="submit" class="geex-btn geex-btn--primary"><?php echo isset($editData) ? 'Update' : 'Tambah'; ?></button>
                </div>
            </form>
            <div class="container">
                <div class="row">
                    <div class="container mt-4">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Poster 565 x 252</th>
                                    <th>Poster 565 x 252 (2)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Ambil data dari tabel dan tampilkan di tabel
                                $stmt = $conn->query("SELECT * FROM poster_tbl");
                                while ($row = $stmt->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><img src='assets/image_db/userPage/" . htmlspecialchars($row['poster_1']) . "' width='100'></td>";
                                    echo "<td><img src='assets/image_db/userPage/" . htmlspecialchars($row['poster_2']) . "' width='100'></td>";
                                    echo "<td>
                                        <a href='?id=" . htmlspecialchars($row['id']) . "' class='btn btn-success'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            var entry_id = this.getAttribute('data-id');
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
                    window.location.href = "deleteProduct.php?id=" + entry_id;
                }
            });
        });
    });
</script>

<!-- SweetAlert notification for success message -->
<script>
    <?php if (isset($success)): ?>
        Swal.fire({
            title: 'Sukses!',
            text: 'Data berhasil disimpan!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#246c3c'
        });
    <?php endif; ?>
</script>


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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
