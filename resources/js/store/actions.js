import { UserService, AuthenticationError } from "../services/user.service";
import { ProfileService} from "../services/profile.service";
import router from '../router'
import { TokenService } from "../services/token.service";

const actions = {

    async editUser({commit},data){
        commit('miscellaneous/LOAD_CONTENT')

        let response = await UserService.editUser(data)

        if (response.data.message === 'successful') {
            commit('RELOAD_SUCCESS', response.data.user)
        } else{
            
        }

        commit('miscellaneous/LOAD_CONTENT_COMPLETE')
    },

    async profileGet({commit},{account, accountId}){
        
        commit('LOAD_PROFILE')

        if ((account === 'learner' ||
            account === 'parent' ||
            account === 'facilitator' ||
            account === 'school' ||
            account === 'professional')
        ) {
            let response = await ProfileService.profileGet({account,accountId})
            if (response.status != 200) {

                commit('LOAD_PROFILE_COMPLETE')
                router.push({
                    name: '404',
                    params: {
                        // message: "something might have happened or you are accessing a profile which doen't exits."
                        message: response.data.message
                    }
                }) 
            }else{
                commit('profile/GET_PROFILE_SUCCESS',response.data)
            }
        } else {
            router.push({
                name: '404',
                params:{
                    message: `The url you entered doesn't work. Replace ${account} with learner, parent, or any other user type.`
                }
            })
        }

        commit('LOAD_PROFILE_COMPLETE')
    },
    async login ({commit}, credentials){
        commit('LOGIN_REQUEST')
        commit('RELOAD_REQUEST')
        try {
            const data = await UserService.login(credentials)

            if (!data.errors) {
                commit('LOGIN_SUCCESS', data.token)
                commit('RELOAD_SUCCESS', data.user)
                commit('CLEAR_VALIDATION_ERRORS')
                if (data.token) {
                    router.push( router.history.current.query.redirectTo || '/')
                }
            } else {
                commit('VALIDATION_ERRORS', data)
            }
            
        } catch (e) {
            if (e instanceof AuthenticationError) {
                commit('LOGIN_FAILURE',e)
            }
            commit('LOGIN_FAILURE',e)
        }

    },
    async reloadUser ({commit,dispatch}){
        commit('RELOAD_REQUEST')
        try {
            
            const data = await UserService.refreshUser()
            if (!data.errors) {
                
                commit('RELOAD_SUCCESS', data.user)
            } else {
                // TokenService.removeToken()
                // router.push({
                //     name: 'login',
                //     query: {
                //         redirectTo: router.currentRoute
                //     }
                // })
                dispatch('logout')
            }
        }catch (e){
            TokenService.removeToken()
            // router.push('/login')
            console.log(e)
            if (e && e.response.config.url.includes('api/user')) {
                commit('RELOAD_FAILURE',e.response.data.message)
            }
            
            dispatch('logout')
        }
    },

    async register ({commit}, credentials){
        commit('LOGIN_REQUEST')
        commit('RELOAD_REQUEST')

        try {
            const data = await UserService.register(credentials)
            // console.log('data',data)
            if (!data.errors) {
                commit('LOGIN_SUCCESS', data.token)
                commit('RELOAD_SUCCESS', data.user)
                commit('CLEAR_VALIDATION_ERRORS')
                if (data.token) {
                    router.push( router.history.current.query.redirectTo || '/welcome')
                }
                
            } else {
                commit('VALIDATION_ERRORS', data)
            }
            
        } catch (e) {
            if (e instanceof AuthenticationError) {
                console.log('action errors', e.errors)

                commit('LOGIN_FAILURE',e)
                commit('VALIDATION_ERRORS', e)
            }
        }
    },

    logout({commit}){
        UserService.logout()
        commit('LOGOUT')
        router.push('/login')
    },

    refreshToken({commit, state}){
        if (!state.refreshTokenPromise) {
            const p = UserService.refreshToken()

            commit('REFREESH_TOKEN_PROMISE', p)

            p.then(
                response =>{
                    commit('REFREESH_TOKEN_PROMISE', null)
                    commit('LOGIN_SUCCESS', response)
                }
            ).catch(e =>{
                commit('REFREESH_TOKEN_PROMISE', null)
            } )
        }

        return state.refreshTokenPromise
    },

    clearValidation({commit}){
        commit('CLEAR_VALIDATION_ERRORS')
    }
}

export default actions