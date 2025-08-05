// public/assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const mobileMenuToggle = document.querySelector('.menu-toggle.mobile');
    const desktopMenuToggle = document.querySelector('.menu-toggle.desktop');

    // 1. Toggle untuk Mobile (Menampilkan/Menyembunyikan sidebar)
    if (mobileMenuToggle && sidebar) {
        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }

    // 2. Toggle untuk Desktop (Memperkecil/Memperlebar sidebar)
    if (desktopMenuToggle && sidebar) {
        desktopMenuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Menutup sidebar mobile jika user klik di luar area sidebar
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !mobileMenuToggle.contains(event.target) && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        }
    });

});
// --- Tambahan untuk Profile Dropdown ---
const profileDropdown = document.querySelector('.profile-dropdown');
const dropdownMenu = document.querySelector('.dropdown-menu');

if (profileDropdown) {
    profileDropdown.addEventListener('click', function(event) {
        // Mencegah event "click outside" langsung menutup dropdown
        event.stopPropagation();
        this.classList.toggle('active');
    });
}

// Menutup dropdown jika user klik di luar area
window.addEventListener('click', function(e) {
    if (profileDropdown && profileDropdown.classList.contains('active')) {
        profileDropdown.classList.remove('active');
    }
});