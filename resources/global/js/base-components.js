// jQuery
window.$ = window.jQuery = require('jquery');

window.checkInputLabelStates = function () {
    $('.form-control').each(function () {
        const $formControl = $(this);

        $formControl.val() && $formControl.addClass("not-empty");
    });
};

// PopperJS
window.Popper = require('popper.js');

// Bootstrap 4
require('bootstrap');

// Data tables
window.dt = require('datatables.net-bs4');

// WebFont loader
window.WebFont = require('webfontloader');

// CSS Animations
require('animate.css');
