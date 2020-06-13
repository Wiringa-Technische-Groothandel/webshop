import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import axios from '../services/axios'
import * as auth from '../services/auth'

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

axios.interceptors.response.use(null, (error) => {
    if (error.config && error.response && error.response.status === 401) {
        return auth.refreshToken(true)
            .then((data) => {
                if (! data.success) {
                    store.commit('auth/logout');

                    Promise.reject(error);
                } else {
                    store.commit('auth/login', {
                        token: data.token,
                        expires_at: data.expires_at
                    });

                    error.config.headers.Authorization = `Bearer ${data.token}`;

                    return axios.request(error.config);
                }
            }).catch(error => {
                store.commit('auth/logout');
            })
    }

    return Promise.reject(error);
});

Vue.prototype.$http = axios;
Vue.prototype.$auth = auth;

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
    computed: {
        isLoggedIn () {
            return this.$store.getters['auth/isLoggedIn'];
        }
    },
    mounted () {
        document.body.style.opacity = "1";

        $('[data-toggle="tooltip"]').tooltip();
    }
});
