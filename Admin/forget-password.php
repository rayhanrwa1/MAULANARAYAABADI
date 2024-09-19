<?php
session_start(); // Start session

// Check if the user is already logged in
if (isset($_SESSION['username_employee'])) {
    header('Location: index.php'); // Redirect to the appropriate page
    exit();
}

include 'connection.php'; // Include database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$success = false; // Variable to track successful submission
$error = false;   // Variable to track errors (number not found)
$db_error = false; // Variable to track database connection errors

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emp_number'])) {
    $emp_number = trim($_POST['emp_number']); // Get employee number and trim whitespace
    
    if (!empty($emp_number)) {
        // Prepare and execute the query to check if emp_number exists
        $query = "SELECT emp_number, employee_name, employee_phone, photo FROM tbl_emp_7tt8 WHERE emp_number = ?";
        $stmt = $conn->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("s", $emp_number);
            if ($stmt->execute()) {
                // Bind result variables
                $stmt->bind_result($emp_number_result, $employee_name, $employee_phone, $photo);
                
                if ($stmt->fetch()) {
                    // Close the first statement
                    $stmt->close();

                    // If number exists, insert data into tbl_access_guard55
                    $updateQuery = "INSERT INTO tbl_access_guard55 (emp_number, employee_name, employee_phone, photo, forgot_password_request_at) VALUES (?, ?, ?, ?, NOW())";
                    $stmt_insert = $conn->prepare($updateQuery);
                    
                    if ($stmt_insert) {
                        $stmt_insert->bind_param("ssss", $emp_number_result, $employee_name, $employee_phone, $photo);
                        if ($stmt_insert->execute()) {
                            $success = true;
                        } else {
                            error_log("Failed to execute insert query: " . $stmt_insert->error);
                            $db_error = true;
                        }
                        $stmt_insert->close();
                    } else {
                        error_log("Failed to prepare insert query: " . $conn->error);
                        $db_error = true;
                    }
                } else {
                    $error = true; // Number not found
                }
            } else {
                error_log("Failed to execute select query: " . $stmt->error);
                $db_error = true;
            }
        } else {
            error_log("Failed to prepare select query: " . $conn->error);
            $db_error = true;
        }
    } else {
        $error = true;
    }

    $conn->close();
}
?>

<!doctype html>
<html lang="en" dir="ltr">
<?php $title='Forgot-Password' ?>
<?php include './partials/head.php' ?>

<!-- SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/noInternetcon.js"></script>

<body class="geex-dashboard authentication-page">
    <main class="geex-content">
        <div class="geex-content__authentication geex-content__authentication--forgot-password">
            <div class="geex-content__authentication__content">
                <div class="geex-content__authentication__content__wrapper">
                    <div class="geex-content__authentication__content__logo">
                        <a href="index.php">
                            <img class="logo-lite" src="assets/img/logo.svg" alt="logo">
                            <img class="logo-dark" src="assets/img/logo.svg" alt="logo">
                        </a>
                    </div>

                    <!-- SweetAlert Notification -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            <?php if ($success): ?>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terima kasih',
                                    text: 'Permintaan Anda sedang dalam peninjauan. Tunggu proses selama 1 x 24 jam.',
                                    confirmButtonColor: '#246c3c',
                                    confirmButtonText: 'Kembali Masuk'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = 'signin.php';
                                    }
                                });
                            <?php elseif ($error): ?>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Nomor Tidak Ditemukan',
                                    text: 'Nomor Identifikasi Karyawan tidak ditemukan atau terjadi kesalahan. Silakan coba lagi.',
                                    confirmButtonColor: '#246c3c'
                                });
                            <?php elseif ($db_error): ?>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan Sistem',
                                    text: 'Terjadi kesalahan pada sistem. Silakan coba lagi nanti.',
                                    confirmButtonColor: '#246c3c'
                                });
                            <?php endif; ?>
                        });
                    </script>

                    <!-- Form -->
                    <form id="forgotPasswordForm" class="geex-content__authentication__form" method="POST" action="">
                        <h2 class="geex-content__authentication__title">Lupa Password?</h2>
                        <p class="geex-content__authentication__desc">Mohon Verifikasi Nomor Identifikasi Karyawan</p>
                        <div class="geex-content__authentication__form-group">
                            <label for="emp_number">Nomor Identifikasi Karyawan</label>
                            <input type="text" id="emp_number" name="emp_number" placeholder="Nomor Identifikasi Karyawan" required>
                            <i class="uil uil-user"></i>
                        </div>
                        <button type="submit" class="geex-content__authentication__form-submit">Selanjutnya</button>
                        <a href="signin.php" class="geex-content__authentication__form-submit return-btn" style="width: 100%;">Kembali Masuk</a>
                    </form>
                    
                </div>
            </div>  
            <div class="geex-content__authentication__img">
                <img src="./assets/img/authentication.svg" alt="">
            </div>
        </div>
    </main>

    <!-- inject:js-->
    <?php include './partials/script.php' ?>
    <!-- endinject-->
</body>
</html>
