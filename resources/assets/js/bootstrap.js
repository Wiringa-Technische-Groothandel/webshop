
// Promise polyfill
import Promise from 'promise-polyfill';
window.Promise = Promise;

// Object.assign polyfill
require('./polyfills/object-assign');

// IntersectionObserver polyfill
require('intersection-observer');

// lodash (whatever that is...)
window._ = require('lodash');

// jQuery
window.$ = window.jQuery = require('jquery');

// Popper for Bootstrap 4
import Popper from 'popper.js';
window.Popper = Popper;

// Bootstrap 4
require('bootstrap');

// Lightbox 2
window.lightbox = require('lightbox2');

// ChartJS
window.Chart = require('chart.js');

// VueJS
window.Vue = require('vue');

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


// Tiny slider
import {tns} from 'tiny-slider/src/tiny-slider.module';
window.tns = tns;
