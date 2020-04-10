import axios from "../../services/axios";

export default {
    namespaced: true,
    state: {
        user: null
    },
    getters: {
        isLoggedIn(state) {
            return !!state.user;
        }
    },
    mutations: {
        login(state, {username, password}) {
            axios.post(route('admin.api.login'), { username, password }).then(response => {
                state.user = response.data.user;
            });
        },
        check(state) {
            axios.get(route('admin.api.me')).then(response => {
                state.user = response.data;
            });
        },
        logout(state) {
            if (!state.user) {
                return;
            }

            axios.post(route('admin.api.logout')).then(() => {
                state.user = null;
            });
        }
    }
}