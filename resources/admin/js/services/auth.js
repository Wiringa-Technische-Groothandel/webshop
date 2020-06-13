import getUnixTime from 'date-fns/getUnixTime';
import axios from 'axios';

const KEY_TOKEN = 'admin-token';
const KEY_TOKEN_EXPIRY = 'admin-token-expiry';

/**
 * Get a refreshed token.
 *
 * @returns {Promise<string|boolean|*>}
 */
export const refreshToken = async (force = false) => {
    if (! hasAccessToken()) {
        return false;
    }

    if (! isTokenExpired() && !force) {
        return getAccessToken();
    }

    return await loginWithToken(getAccessToken());
};

/**
 * Login.
 *
 * @param username
 * @param password
 * @returns {Promise<*>}
 */
export const login = async (username, password) => {
    return await axios.post(route('admin.api.login'), { username, password })
        .then(handleLoginResponse);
};

/**
 * Login using a token. (Refresh the token)
 *
 * @param token
 * @returns {Promise<*>}
 */
export const loginWithToken = async (token) => {
    return await axios.post(route('admin.api.refresh-token'), { token })
        .then(handleLoginResponse);
};

/**
 * Logout.
 *
 * @returns {Promise<*>}
 */
export const logout = async () => {
    return await axios.post(route('admin.api.logout'))
        .then(handleLogout);
};

/**
 * Get the access token from local storage.
 *
 * @returns {string}
 */
export const getAccessToken = () => localStorage.getItem(KEY_TOKEN);

/**
 * Check if there is an access token in local storage.
 *
 * @returns {boolean}
 */
export const hasAccessToken = () => Boolean(getAccessToken());

/**
 * Get the expiry timestamp of the access token from local storage.
 *
 * @returns {string}
 */
export const getExpiryTimestamp = () => localStorage.getItem(KEY_TOKEN_EXPIRY);

/**
 * Check if the token is expired.
 *
 * @returns {boolean}
 */
export const isTokenExpired = () => {
    if (! hasAccessToken()) {
        return true;
    }

    return getExpiryTimestamp() < getUnixTime(new Date);
};

/**
 * Handle a logout response.
 *
 * @private
 * @param response
 * @returns {*}
 */
const handleLogout = (response) => {
    localStorage.removeItem('admin-token');
    localStorage.removeItem('admin-token-expiry');

    return response.data;
};

/**
 * Handle a login response.
 *
 * @private
 * @param response
 * @returns {*}
 */
const handleLoginResponse = (response) => {
    if (response.data.success) {
        localStorage.setItem(KEY_TOKEN, response.data.token);
        localStorage.setItem(KEY_TOKEN_EXPIRY, response.data.expires_at);
    } else {
        if (response.data.logout) {
            handleLogout();
        }
    }

    return response.data;
};
