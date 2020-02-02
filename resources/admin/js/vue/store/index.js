import Vuex from 'vuex'

import auth from './auth'
import cache from './cache'

export default new Vuex.Store({
    modules: {
        auth,
        cache
    }
})