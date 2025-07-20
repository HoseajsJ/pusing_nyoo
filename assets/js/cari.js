// assets/js/cari.js
function toggleMenu() {
    const slideMenu = document.getElementById('slideMenu');
    const overlay = document.getElementById('overlay');

    // Periksa posisi 'right' saat ini
    if (slideMenu.style.right === '0px' || window.getComputedStyle(slideMenu).right === '0px') {
        // Jika menu terlihat (right: 0px), sembunyikan
        slideMenu.style.right = '-250px'; // Sembunyikan menu ke kanan
        overlay.style.display = 'none'; // Sembunyikan overlay
    } else {
        // Jika menu tersembunyi, tampilkan
        slideMenu.style.right = '0px'; // Tampilkan menu
        overlay.style.display = 'block'; // Tampilkan overlay
    }
}
