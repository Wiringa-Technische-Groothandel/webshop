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

import Notification from '../../global/vue/Notification'
import Block from '../vue/Content/Block'
import Descriptions from '../vue/Content/Descriptions'

window.vm = new Vue({
    el: '#app',
    components: {
        'notification': Notification,
        'block': Block,
        'descriptions': Descriptions,
    },
    data () {
        return {
            filter: {}
        }
    }
});