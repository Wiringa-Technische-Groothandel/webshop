// lodash
window._ = require('lodash');

// jQuery
window.$ = window.jQuery = require('jquery');

// Popper for Bootstrap 4
import Popper from 'popper.js';
window.Popper = Popper;

// Sweetalert
import swal from 'sweetalert';
window.swal = swal;

// ChartJS
window.Chart = require('chart.js');

// VueJS
window.Vue = require('vue');

require('animate.css');

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}