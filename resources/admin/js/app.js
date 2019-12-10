WebFont.load({
    google: {
        families: ['Muli:300,400,600,800']
    }
});

$(document).ready(function () {
    var $page = $('#page-wrapper');
    var $navToggle = $('#toggle-navigation');

    $navToggle.on('click', function () {
        if ($page.hasClass('show-nav')) {
            $page.removeClass('show-nav');
        } else {
            $page.addClass('show-nav');
        }
    });
});

require('./vue');