const Notification = () => import(/* webpackChunkName: 'vue-components' */ '../../global/vue/Notification');
const Block = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Content/Block');
const Descriptions = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Content/Descriptions');

window.vm = new Vue({
    el: '#app',
    components: {
        Notification,
        Block,
        Descriptions,
    },
    data () {
        return {
            filter: {}
        }
    }
});
