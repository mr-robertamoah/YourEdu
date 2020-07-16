import state from './state'
import getters from './getters'
import actions from './actions'
import mutations from './mutations'
import profile from './modules/profile'
import miscellaneous from './modules/miscellaneous'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        profile,
        miscellaneous,
    },
    state,
    getters,
    mutations,
    actions
})