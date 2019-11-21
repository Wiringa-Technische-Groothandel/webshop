require('../../global/js/head');

// Bootstrap 4
require('bootstrap');

document.documentElement.setAttribute('data-useragent', navigator.userAgent);

WebFont.load({
    google: {
        families: ['Muli:300,400,600,800', 'Oswald:500,700']
    }
});

window.checkInputLabelStates = function () {
    $('.form-control').each(function () {
        const $formControl = $(this);

        $formControl.val() && $formControl.addClass("not-empty");
    });
};

$(document).ready(function () {
    let $formControls = $('.form-control');

    $formControls.on("change", function(event) {
        const $target = $(event.target);

        $target.val() ? $target.addClass("not-empty") : $target.removeClass("not-empty");
    });

    checkInputLabelStates();
});

checkInputLabelStates();

getVue().then(({ default: Vue }) => {
    window.Vue = Vue;

    import(/* webpackChunkName: 'frontend-vue' */ './vue');
}).catch(() => {
    console.error('Failed to init Vue');
});
