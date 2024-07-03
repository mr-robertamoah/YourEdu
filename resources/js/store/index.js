import state from './state'
import getters from './getters'
import actions from './actions'
import mutations from './mutations'
import profile from './modules/profile'
import home from './modules/home'
import miscellaneous from './modules/miscellaneous'
import dashboard from './modules/dashboard'

import { createStore } from 'vuex'

const store = createStore({
    modules: {
        profile,
        miscellaneous,
        home,
        dashboard,
    },
    state,
    getters,
    mutations,
    actions
})

export default store