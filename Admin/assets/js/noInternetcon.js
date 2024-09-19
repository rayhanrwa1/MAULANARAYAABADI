// Include SweetAlert2 CDN
const script = document.createElement('script');
script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
document.head.appendChild(script);

// Function to show a SweetAlert2 notification
function showNoInternetAlert() {
    Swal.fire({
        icon: "question",
        title: 'Koneksi Internet Tidak Ada',
        text: 'Sepertinya Anda tidak terhubung ke internet. Harap periksa koneksi Anda dan coba lagi.',
        confirmButtonText: 'OK'
    });
}

// Function to check network status
function checkInternetConnection() {
    if (!navigator.onLine) {
        showNoInternetAlert();
    }
}

// Check internet connection when the page loads
window.addEventListener('load', function() {
    // Wait for SweetAlert2 script to load before checking connection
    script.onload = function() {
        checkInternetConnection();
    };
});

// Check internet connection when the browser goes online or offline
window.addEventListener('online', function() {
    // Optionally, you can notify the user when the connection is restored
    console.log('Koneksi pulih');
});

window.addEventListener('offline', function() {
    showNoInternetAlert();
});
