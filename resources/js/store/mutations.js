const mutations = {
    LOGIN_REQUEST(state){
        state.authenticating = true
        state.authenticatingErrorCode = 0
        state.authenticatingErrorMessage = ''
    },

    LOGIN_SUCCESS(state, token){
        // console.log('login success data',data)
        state.authenticating = false
        state.accessToken = token
        state.loggedin = true
        // state.user = data.user
    },

    LOGIN_FAILURE(state, error){
        state.authenticating = false
        state.authenticatingErrorCode = error.errorCode
        state.authenticatingErrorMessage = error.errorMessage
        state.loggedin = false
    },
    
    RELOAD_REQUEST(state){
        state.authenticatingUser = true
        state.authenticatingErrorCode = 0
        state.authenticatingErrorMessage = ''
    },

    RELOAD_SUCCESS(state, user){
        state.authenticatingUser = false
        state.user = user
        state.loggedin = true
    },

    RELOAD_FAILURE(state, error){
        state.authenticatingUser = false
        state.authenticatingErrorCode = 401
        state.authenticatingErrorMessage = error
        state.loggedin = false
    },

    LOGOUT(state){
        state.accessToken = ''
        state.user = null
        state.loggedin = false
    },

    REFREESH_TOKEN_PROMISE(state,promise){
        state.REFREESH_TOKEN_PROMISE = promise
    },

    VALIDATION_ERRORS(state,{errors}){
        state.validationErrors = errors
    },

    CLEAR_VALIDATION_ERRORS(state){
        state.validationErrors = null
        state.authenticatingErrorMessage = null
    },

    LOAD_PROFILE_COMPLETE(state){
        state.loading = false
    },

    LOAD_PROFILE(state){
        state.loading = true
    },

    //////////////////////////////////////////////// follow requests
    START_REQUEST(state){
        state.requestingStatus = true
    },


    END_REQUEST(state){
        state.requestingStatus = false
    },

    // GET_FOLLOW_SUCCESS(state, data){
    //     state.userFollowRequest = data.requests
    // },

    FOLLOW_SUCCESS(state, data){
        let accountRequestsIndex = state.userFollowRequest.findIndex(userFollow=>{
            return userfollow.requestsReceived.id = data.requestId
        })

        if (accountRequestsIndex > -1) {
            state.userFollowRequest.splice(accountRequestsIndex, 1)
        }
    },

    FOLLOW_BACK_SUCCESS(state){
        state.profile.followings += 1
    },

    ///////////////////////////////////////////////////////// accounts

    ACCOUNT_CREATE_SUCCESS(state,data){
        if (data.owned_profile.account_type === 'learner') {
            state.user.learner = data.learner
        } else if (data.owned_profile.account_type === 'parent') {
            state.user.parent = data.parent
        } else if (data.owned_profile.account_type === 'facilitator') {
            state.user.facilitator = data.facilitator
        } else if (data.owned_profile.account_type === 'professional') {
            state.user.professionals.push(data.professional)
        } else if (data.owned_profile.account_type === 'school') {
            state.user.schools.push(data.school)
        } else if (data.owned_profile.account_type === ' ') {
            state.user.groups.push(data.group)
        }
        state.user.owned_profiles.push(data.owned_profile)
    },
}

export default mutations