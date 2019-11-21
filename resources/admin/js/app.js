// Bootstrap 4 with material stuff
require('./bootstrap-material-design.min');

// ChartJS
window.getChartJs = () => import('chart.js');

// ChartJS
getChartJs().then(({default: Chart}) => {
    Chart.defaults.global.maintainAspectRatio = false;

    window.Chart = Chart;
}).catch('Failed to init ChartJS');

// Names of the months
window.months = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni',
    'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];

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

getVue().then(({default: Vue}) => {
    window.Vue = Vue;

    import(/* webpackChunkName: 'admin-vue' */ './vue');
}).catch(() => {
    console.error('Failed to init Vue');
});
