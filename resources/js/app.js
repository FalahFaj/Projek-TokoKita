// 1. Impor bootstrap.js yang akan menangani jQuery, Select2, dan Bootstrap JS.
// Ini harus menjadi yang pertama untuk memastikan dependensi siap.
import './bootstrap';

// 2. Impor CSS dan kelas POSSystem.
import '../css/pos.css';
import POSSystem from './pos.js';

// Jalankan inisialisasi setelah DOM siap
$(document).ready(function() {
    // Periksa apakah kita berada di halaman POS sebelum menginisialisasi.
    // Ini mencegah error jika skrip dijalankan di halaman lain.
    if ($('#productsGrid').length) {
        // Buat instance dari POSSystem dan simpan secara global agar bisa diakses
        // dari event handler lain jika diperlukan.
        window.pos = new POSSystem();
    }
});

// Karena event handler untuk tombol [data-action] ada di dalam kelas POSSystem,
// kita tidak perlu lagi mendaftarkannya secara terpisah di sini.
