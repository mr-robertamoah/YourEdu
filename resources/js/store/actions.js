import { UserService, AuthenticationError } from "../services/user.service";
import { ProfileService} from "../services/profile.service";
import { TokenService } from "../services/token.service";

const actions = {

    async acceptFollowRequest({commit}, data){
        commit('START_REQUEST')

        let response = await UserService.acceptFollowRequest(data)

        commit('END_REQUEST')
        if (response.data.message === 'successful') {
            commit('FOLLOW_BACK_SUCCESS',response.data.following)
            return 'successful'
        } else{
            return 'unsuccessful'
        }

    },

    async declineFollowRequest({commit}, data){
        commit('START_REQUEST')

        let response = await UserService.declineFollowRequest(data)

        commit('END_REQUEST')
        if (response.data.message === 'successful') {
            commit('DECLINE_FOLLOW_SUCCESS')
            return 'successful'
        } else{
            return 'unsuccessful'
        }

    },
    async editUser({commit},data){
        commit('miscellaneous/LOAD_CONTENT')

        let response = await UserService.editUser(data)

        commit('miscellaneous/LOAD_CONTENT_COMPLETE')
        if (response.data.message === 'successful') {
            commit('RELOAD_SUCCESS', response.data.user)
            return 'successful'
        } else{
            return 'unsuccessful'
        }
    },
    async getFollowers({commit}){
        let response = await UserService.followersGet()
        
        if (response.data.data) {
            commit('FOLLOWERS_SUCCESS', response.data.data)
        } else{
            console.log('error',response);
            commit('FOLLOWERS_FAILURE')
        }
    },
    async getFollowings({commit}){
        let response = await UserService.followingsGet()
        
        if (response.data.data) {
            commit('FOLLOWINGS_SUCCESS', response.data.data)
        } else{
            console.log('error',response);
            commit('FOLLOWINGS_FAILURE')
        }
    },
    async userRequests({},data){
        let response = await UserService.getUserRequests(data)

        if (response.data.data) {
            return {
                status: true,
                data: response.data.data,
                next: response.data.links.next
            }
        } else{
            return {status: false, response}
        }
    },
    addProfile({commit},data){
        commit('ADD_PROFILE',data)
    },
    async schoolRequestResponse({commit},data){
        let response = await UserService.schoolRequestResponse(data)

        if (response.data.message === 'successful') {
            if (data.from === 'school' && data.type === 'admin') {
                commit('ADD_PROFILE',response.data.data)
            }
            return {
                status: true,
            }
        } else{
            return {status: false, response}
        }
    },
    async userNotifications({},data){
        let response = await UserService.userNotifications(data)

        if (response.data.data) {
            return {
                status: true,
                data: response.data.data,
                next: response.data.links.next
            }
        } else{
            return {status: false, response}
        }
    },
    async markNotifications(){
        let response = await UserService.markNotifications()

        if (response.data.message === 'successful') {
            return {status: true}
        } else{
            return {status: false, response}
        }
    },
    async markOtherNotifications({},data){
        let response = await UserService.markOtherNotifications(data)

        if (response.data.message === 'successful') {
            return {status: true}
        } else{
            return {status: false, response}
        }
    },
    updateUserFollows({commit},data){
        commit('UPDATE_USER_FOLLOWS',data)
    },
    addUserFollower({commit},data){
        commit('ADD_USER_FOLLOWER',data)
    },

    ////////////////////////////// profiles

    async createAccount({commit}, data){
        let response = await UserService.accountCreate(data)

        if (response.data.message === 'successful') {
            if (data.get('creator') === 'user') {                
                commit('ACCOUNT_CREATE_SUCCESS',{
                    profile: response.data.profile
                })
                return {status:true}
            } else {
                if (data.get('creator') === 'school') {
                    commit('dashboard/SCHOOL_ADD_ACCOUNTS',{
                        accountOne: response.data.accountOne,
                        accountTwo: response.data.accountTwo,
                    },{root: true})
                }
                return {
                    status:true,
                    userOne: response.data.userOne,
                    accountOne: response.data.accountOne,
                    userTwo: response.data.userTwo,
                    accountTwo: response.data.accountTwo,
                }
            }
        } else {
            return {status:false, response}
        }
    },

    async profileGet({commit},{account, accountId}){
        
        commit('LOAD_PROFILE')

        let response = await ProfileService.profileGet({account,accountId})
        let status = true

        if (response.status != 200) status = false
        else commit('profile/GET_PROFILE_SUCCESS',response.data)

        commit('LOAD_PROFILE_COMPLETE')

        return {status}
    },

    /////////////////////////////////////////////////////////////////// user

    async findUser({commit}, data){
        let response = await UserService.findUser(data)

        if (response.data.data) {
            return {
                status: true,
                next: response.data.links.next,
                users: response.data.data
            }
        } else {
            return {status: false}
        }
    },

    async getUserSaved({commit}, data){
        let response = await UserService.userSavedGet(data)

        if (response.data.data) {
            return {
                next: response.data.links.next,
                data: response.data.data
            }
        } else {
            return 'unsuccessful'
        }
    },

    async getUserFlagged({commit}, data){
        let response = await UserService.userFlaggedGet(data)

        if (response.data.data) {
            return {
                next: response.data.links.next,
                data: response.data.data
            }
        } else {
            return 'unsuccessful'
        }
    },

    setCurrentRouteName({commit}, routeName) {
        commit('SET_CURRENT_ROUTE_NAME', routeName)
    },

    async login ({commit}, credentials){
        commit('LOGIN_REQUEST')
        commit('RELOAD_REQUEST')
        try {
            const data = await UserService.login(credentials)

            let res = {}

            if (!data.errors) {
                res.status = true
                commit('LOGIN_SUCCESS', data.token)
                commit('RELOAD_SUCCESS', data.user)
                commit('CLEAR_VALIDATION_ERRORS')
                if (data.token) {
                    res.token = data.token
                }
            } else {
                res.status = false
                commit('VALIDATION_ERRORS', data)
            }
            
            return res
        } catch (e) {
            if (e instanceof AuthenticationError) {
                commit('LOGIN_FAILURE',e)
            }
            commit('LOGIN_FAILURE',e)
            // console.log('error in login actions',error)
        }

    },
    async reloadUser ({commit,dispatch}){
        commit('RELOAD_REQUEST')
        try {
            
            const data = await UserService.refreshUser()
            if (!data.errors) {
                
                commit('RELOAD_SUCCESS', data.user)
            } else {

                dispatch('logout')
            }
        }catch (e){
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
            console.log('data in actions',data)
            let res = {}

            if (!data.errors) {
                res.status = true
                commit('LOGIN_SUCCESS', data.token)
                commit('RELOAD_SUCCESS', data.user)
                commit('CLEAR_VALIDATION_ERRORS')
                if (data.token) res.token = true
            } else {
                console.log('error in actions',data)
                commit('VALIDATION_ERRORS', data)
            }
            
            return res
        } catch (e) {
            console.log('action errors', e.errors)
            if (e instanceof AuthenticationError) {

                commit('LOGIN_FAILURE',e)
                commit('VALIDATION_ERRORS', e)
                return 'unsuccessful'
            }
        }
    },

    async logout({commit}){
        let response = await UserService.logout()
        let status = false

        if (response.data.message === 'successful') {
            commit('LOGOUT')
            TokenService.removeToken()
            status = true
        } else {
            console.log(response);
        }

        return {status}
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