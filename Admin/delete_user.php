<?php
include 'connection.php'; // Include the database connection

// Get the employee ID from the query string
$emp_number = isset($_GET['id']) ? $_GET['id'] : '';

if (!empty($emp_number)) {
    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tbl_emp_7tt8 WHERE emp_number = ?");
    $stmt->bind_param('s', $emp_number);
    
    if ($stmt->execute()) {
        // Redirect to a PHP page with a JavaScript snippet to handle the redirect and loading message
        echo '<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Memproses...</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: "Memproses...",
                    text: "Harap tunggu sementara kami memproses permintaan Anda.",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                setTimeout(() => {
                    window.location.href = "index.php?success=1";
                }, 5000); // 5 detik delay
            </script>
        </body>
        </html>';
    } else {
        // Redirect to a PHP page with a JavaScript snippet to handle the redirect and loading message
        echo '<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Memproses...</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: "Memproses...",
                    text: "Harap tunggu sementara kami memproses permintaan Anda.",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                setTimeout(() => {
                    window.location.href = "index.php?success=0";
                }, 5000); // 5 detik delay
            </script>
        </body>
        </html>';
    }
    exit();
}
?>
