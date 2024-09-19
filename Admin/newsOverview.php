<!doctype html>
<html lang="en" dir="ltr">
<?php
$title = 'index'; 
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

// Determine sort order
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'newest'; // Default to 'newest'

// Adjust SQL query based on sort order
$sql = "SELECT headline, publication_date, content_summary, image_path FROM tbl_media_announcements ORDER BY publication_date ".($sort_order == 'oldest' ? 'ASC' : 'DESC');
$result = $conn->query($sql);

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
                <h2 class="geex-content__header__title">Halaman Review</h2>
                <p class="geex-content__header__subtitle">Halaman Review Berita PT Maulana Raya Abadi</p>
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

        <div class="dropdown">
            <button class="dropdown-btn">Sortir <i class="uil uil-sort"></i></button>
            <div class="dropdown-content">
                <a href="?sort=newest" class="dropdown-item" data-sort="newest">Terbaru</a>
                <a href="?sort=oldest" class="dropdown-item" data-sort="oldest">Terlama</a>
            </div>
        </div>

        <div class="geex-content__blog">
            <div class="geex-content__blog__wrapper">
                <div class="row">
                    <?php
                        // Display data from database
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<div class="col-xxl-3 col-xl-4 col-md-6 mb-25">';
                                echo '    <div class="geex-content__blog__single">';
                                echo '        <div class="geex-content__blog__single__img">';
                                echo '            <a>';
                                echo '                <img src="'.$row['image_path'].'" alt="Single Blog" />';
                                echo '            </a>';
                                echo '        </div>';
                                echo '        <div class="geex-content__blog__single__content">';
                                echo '            <div class="geex-content__blog__single__quickinfo">';
                                echo '                <h5>'.$row['headline'].'</h5>';
                                echo '                <span>'.$row['publication_date'].'</span>';
                                echo '            </div>';
                                echo '            <div class="geex-content__blog__single__title">';
                                echo '                '.$row['content_summary'];
                                echo '            </div>';
                                echo '        </div>';
                                echo '    </div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p>Data tidak ditemukan.</p>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/autoLogout.js"></script>
<script src="assets/js/noInternetcon.js"></script>
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
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?delete=" + entry_id;
                }
            });
        });
    });

    // Check if success message is set
    <?php if (isset($success) && $success): ?>
    Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Berhasil!',
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location = 'newsOverview.php';
    });
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {
        // Load the saved sort order from local storage
        const savedSort = localStorage.getItem('sortOrder') || 'newest';

        // Update the dropdown button text based on the saved sort order
        const dropdownButton = document.querySelector('.dropdown-btn');
        const dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownItems.forEach(item => {
            if (item.getAttribute('data-sort') === savedSort) {
                dropdownButton.textContent = item.textContent;
                dropdownButton.innerHTML = item.textContent + ' <i class="uil uil-sort"></i>';
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });

        // Handle dropdown toggle
        document.querySelectorAll('.dropdown-btn').forEach(button => {
            button.addEventListener('click', function() {
                const dropdown = this.closest('.dropdown');
                dropdown.classList.toggle('show');
            });
        });

        // Handle dropdown item click
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                const sortOrder = this.getAttribute('data-sort');
                localStorage.setItem('sortOrder', sortOrder);
                dropdownButton.textContent = this.textContent;
                dropdownButton.innerHTML = this.textContent + ' <i class="uil uil-sort"></i>';
                document.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Close dropdown if clicked outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.dropdown-btn')) {
                document.querySelectorAll('.dropdown.show').forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    });
</script>
<!-- inject:js-->
<?php include './partials/script.php'; ?>
<!-- endinject-->
</body>
</html>
