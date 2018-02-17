
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// ChartJS
window.Chart.defaults.global.maintainAspectRatio = false;

// Axios
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest'
};

window.randomColor = function () {
    return randomMC.getColor();
};

// Names of the months
window.months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

$(document).ready( function() {
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

Vue.component('notification', require('../components/Notification'));
Vue.component('descriptions', require('../components/Admin/Content/Descriptions'));

window.vm = new Vue({
    el: '#app',
    data () {
        return {
            filter: {}
        }
    }
});