function checkAdminAccess(isAdmin) {
    // Jika bukan admin, tampilkan SweetAlert2 dan alihkan ke halaman lain setelah penutupan
    if (isAdmin != 1) {
        // Buat overlay dengan efek blur
        var overlay = document.createElement('div');
        overlay.id = 'blurOverlay';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.background = 'rgba(255, 255, 255, 0.5)';
        overlay.style.backdropFilter = 'blur(8px)';
        overlay.style.webkitBackdropFilter = 'blur(8px)';
        overlay.style.zIndex = '1059'; // Pastikan z-index lebih rendah dari SweetAlert2
        overlay.style.display = 'block';
        document.body.appendChild(overlay);

        // Tampilkan SweetAlert2
        Swal.fire({
            icon: "error",
            title: "Akses Ditolak",
            text: "Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke halaman utama.",
            confirmButtonText: "Ok",
            confirmButtonColor: "#4b9532",
            didClose: () => {
                // Sembunyikan overlay setelah alert ditutup
                overlay.style.display = 'none';
                window.location.href = "index.php"; // Alihkan ke halaman index
            }
        });
    }
}
