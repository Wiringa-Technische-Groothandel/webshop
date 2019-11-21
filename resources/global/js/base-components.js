// lodash
// window._ = require('lodash');

window.getVue = () => import('vue');

// jQuery
window.$ = window.jQuery = require('jquery');

// Data tables
window.dt = require('datatables.net-bs4');

// Popper for Bootstrap 4
window.Popper = require('popper.js');

// Sweetalert
window.swal = require('sweetalert');

// WebFont loader
window.WebFont = require('webfontloader');

// CSS Animations
require('animate.css');

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;