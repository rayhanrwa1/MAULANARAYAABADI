<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'connection.php'; // Hubungkan ke database

    // Pastikan bahwa 'username_employee' ada dalam sesi sebelum melanjutkan
    if (isset($_SESSION['username_employee'])) {
        $username = $_SESSION['username_employee'];

        // Proses logout dan update status is_active menjadi 0
        $updateQuery = "UPDATE tbl_emp_7tt8 SET is_active = 0 WHERE username_employee = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            session_unset(); // Hapus semua variabel sesi
            session_destroy(); // Hancurkan sesi setelah update sukses
            
            // Kirim respons berhasil ke JavaScript dengan parameter untuk SweetAlert
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Session expired']);
    }
    exit(); // Pastikan tidak ada output tambahan setelah ini
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>

    <!-- Include Google Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert -->

    <style>
        /* Custom style for SweetAlert */
        .swal2-title {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 600;
        }

        .swal2-html-container {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <script>
        // Kirim POST request otomatis untuk logout
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = ''; // Submit form ke halaman yang sama
        document.body.appendChild(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // SweetAlert untuk logout berhasil
                    Swal.fire({
                        title: 'Logout Berhasil',
                        text: 'Anda akan dialihkan ke halaman login!',
                        icon: 'success',
                        timer: 3000, // SweetAlert akan otomatis hilang setelah 3 detik
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'signin.php'; // Alihkan ke halaman login
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.message || 'Logout gagal',
                        icon: 'error'
                    });
                }
            }
        };
        xhr.send(new FormData(form)); // Kirim form via AJAX
    </script>
</body>
</html>
