import axios from "axios";
import {hasAccessToken, getAccessToken} from "./auth";

axios.interceptors.request.use(function (request) {
    if (hasAccessToken()) {
        request.headers.common['Authorization'] = 'Bearer ' + getAccessToken();
    }

    return Promise.resolve(request);
});

export default axios;