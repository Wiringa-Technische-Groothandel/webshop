// ChartJS
window.Chart.defaults.global.maintainAspectRatio = false;

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

Vue.component('notification', require('../../global/vue/Notification'));
Vue.component('block', require('../vue/Content/Block'));
Vue.component('descriptions', require('../vue/Content/Descriptions'));

window.vm = new Vue({
    el: '#app',
    data () {
        return {
            filter: {}
        }
    }
});