import * as auth from '../../services/auth'
import getUnixTime from "date-fns/getUnixTime";

export default {
    namespaced: true,
    state: {
        isLoggedIn: !auth.isTokenExpired(),
        token: auth.getAccessToken() || '',
        expires_at: auth.getExpiryTimestamp() || '',
    },
    getters: {
        isExpired(state) {
            if (!state.token) {
                return true;
            }

            return state.expires_at < getUnixTime(new Date);
        },
        isLoggedIn(state) {
            return state.isLoggedIn;
        },
        hasToken(state) {
            return !!state.token;
        },
        token(state) {
            return state.token;
        }
    },
    mutations: {
        login(state, {token, expires_at}) {
            state.isLoggedIn = true;
            state.token = token;
            state.expires_at = expires_at;
        },
        refresh(state) {
            auth.refreshToken()
                .then((data) => {
                    state.isLoggedIn = true;
                    state.token = data.token;
                    state.expires_at = data.expires_at;
                });
        },
        logout(state) {
            if (!state.isLoggedIn) {
                return;
            }

            state.isLoggedIn = false;
            state.token = '';
            state.expires_at = '';

            auth.logout();
        }
    }
}
