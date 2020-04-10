import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from '../services/axios'

Vue.use(VueRouter);
Vue.use(Vuex);

const store = require('./store').default;
const routes = require('./routes').default;
const router = new VueRouter({
    routes
});

import TopNavigation from '../vue/components/TopNavigation'
import SideNavigation from '../vue/components/SideNavigation'
import LoginForm from '../vue/components/LoginForm'
import Notification from '../../../global/vue/Notification'

router.beforeEach((to, from, next) => {
    document.title = 'WTG Admin' + (to.meta.name ? ' - ' + to.meta.name : '');

    next();
});

Vue.prototype.$http = axios;

window.vm = new Vue({
    el: '#app',
    router,
    store,
    components: {
        TopNavigation,
        SideNavigation,
        LoginForm,
        Notification,
    },
    created () {
        this.$http.get('/airlock/csrf-cookie');
        this.$store.commit('auth/check');
    },
    mounted () {
        document.body.style.opacity = "1";

        $('[data-toggle="tooltip"]').tooltip();
    }
});
