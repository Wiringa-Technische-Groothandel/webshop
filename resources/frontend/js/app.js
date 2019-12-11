import axios from '../../global/js/axios'
window.axios = axios;

require('../../global/js/head');

// Input spinners
require('bootstrap-input-spinner');

$(document).ready(function () {
    $("input[type='number']").inputSpinner();
});

document.documentElement.setAttribute('data-useragent', navigator.userAgent);

WebFont.load({
    google: {
        families: [
            'Muli:300,400,600,800',
            'Oswald:500,700'
        ]
    }
});

require('./vue');
