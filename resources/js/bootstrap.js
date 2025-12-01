/**
 * 1. Setup jQuery Global
 */
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

/**
 * 2. Setup Plugins (Select2)
 */
import select2 from 'select2';
// Inisialisasi Select2
select2(window.jQuery);

/**
 * 3. Setup Bootstrap (PERBAIKAN UTAMA)
 * Kita import sebagai objek dan simpan ke window.bootstrap
 */
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

/**
 * 4. Setup Axios
 */
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
