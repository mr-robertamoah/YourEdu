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
        // state.user = data.user
    },

    LOGIN_FAILURE(state, error){
        state.authenticating = false
        state.authenticatingErrorCode = error.errorCode
        state.authenticatingErrorMessage = error.errorMessage
    },
    
    RELOAD_REQUEST(state){
        state.authenticatingUser = true
        state.authenticatingErrorCode = 0
        state.authenticatingErrorMessage = ''
    },

    RELOAD_SUCCESS(state, user){
        state.authenticatingUser = false
        state.user = user
    },

    RELOAD_FAILURE(state, error){
        state.authenticatingUser = false
        state.authenticatingErrorCode = 401
        state.authenticatingErrorMessage = error
    },

    LOGOUT(state){
        state.accessToken = ''
        state.user = null
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
}

export default mutations