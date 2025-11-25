import './bootstrap';

import 'bootstrap';

document.addEventListener("DOMContentLoaded", function(){
    // Ambil semua elemen dropdown-toggle yang berada di dalam class 'dropend'
    // Ini spesifik untuk submenu
    var submenuToggles = document.querySelectorAll('.dropend .dropdown-toggle');

    submenuToggles.forEach(function(toggle){
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Mencegah menu induk (Admin) tertutup

            // Cari elemen <ul> (submenu) setelah link ini
            var submenu = this.nextElementSibling;

            if(submenu && submenu.classList.contains('dropdown-menu')){
                // Toggle class 'show' untuk menampilkan/menyembunyikan submenu
                submenu.classList.toggle('show');
            }
        });
    });

    var mainDropdowns = document.querySelectorAll('.dropdown');
    mainDropdowns.forEach(function(dropdown){
        dropdown.addEventListener('hidden.bs.dropdown', function () {
            // Jika menu utama (Admin) tertutup, paksa tutup semua submenu
            var submenus = this.querySelectorAll('.dropend .dropdown-menu');
            submenus.forEach(function(sub){
                sub.classList.remove('show');
            });
        });
    });
});
