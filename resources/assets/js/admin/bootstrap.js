window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

import Popper from 'popper.js';
window.Popper = Popper;

require('bootstrap');

window.Chart = require('chart.js');
window.Vue = require('vue');
window.axios = require('axios');
window.randomMC = require('random-material-color');