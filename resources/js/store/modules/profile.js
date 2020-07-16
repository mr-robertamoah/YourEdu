import {ProfileService} from "../../services/profile.service";

const profile = {
    namespaced: true,
    state: () => ({
        account: null,
        profile: null,
        successMessage: null,
    }),
    mutations: {
        GET_PROFILE_SUCCESS(state,data){
            state.account = data.account
            state.profile = data.profile
            state.successMessage = 'successfully got the requested profile'
        },
        UPDATE_PROFILE_SUCCESS(state,data){
            state.profile = data.profile
            state.successMessage = 'successfully updated your profile'
        },
        PROFILE_FAILURE(state, msg){
            state.successMessage = msg
        },
        CLEAR_PROFILE_MSG(state){
            state.successMessage = null
        },
    },
    actions: {
        async updateProfile({commit},data){

            let response = await ProfileService.profileUpdate(data)

            if (response.data.message === 'successful') {
                commit('UPDATE_PROFILE_SUCCESS',response.data)
            }else {
                commit('PROFILE_FAILURE','update was unsuccessul')
            }
        },
        clearMsg({commit}){
            commit('CLEAR_PROFILE_MSG')
        },
    },
    getters: {
        getAccount(state){
            return state.account ? state.account : null
        },
        getProfile(state){
            return state.profile ? state.profile : null
        },
        getMsg(state){
            return state.successMessage ? state.successMessage : null
        },
    }
}

export default profile