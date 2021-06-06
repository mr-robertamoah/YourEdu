import { strings } from "../services/helpers"

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

    FOLLOW_SUCCESS(state, following){
        state.userFollowings.push(following)
    },

    FOLLOWINGS_SUCCESS(state, followings){
        state.userFollowings = followings
    },

    FOLLOWINGS_FAILURE(state){
        
    },

    FOLLOWERS_SUCCESS(state, followers){
        state.userFollowers = followers
    },

    FOLLOWERS_FAILURE(state){

    },

    FOLLOW_BACK_SUCCESS(state, following){
        state.user.follow_requests -= 1
        state.userFollowings.push(followings)
    },

    DECLINE_FOLLOW_SUCCESS(state){
        state.user.follow_requests -= 1
    },

    ADD_USER_FOLLOWER(state, follow){
        state.userFollowers.push(follow)
    },

    UPDATE_USER_FOLLOWS(state, data){
        if (data.type === 'conversation') {
            let ownAccount = null,
                otherAccount = null
            ownAccount = data.conversation.conversationAccounts.find(account=>{
                return account.user_id === state.user.id
            })
            otherAccount = data.conversation.conversationAccounts.find(account=>{
                return account.user_id !== state.user.id
            })
            state.userFollowers.some(follow=>{
                if (strings.getAccount(follow.followedby_type) === otherAccount.account &&
                    follow.followedby_id === otherAccount.accountId &&
                    strings.getAccount(follow.followable_type) === ownAccount.account &&
                    follow.followable_id === ownAccount.accountId) {
                    follow.conversation_id = data.conversation.id
                    return true
                }
            })
            state.userFollowings.some(follow=>{
                if (strings.getAccount(follow.followedby_type) === ownAccount.account &&
                    follow.followedby_id === ownAccount.accountId &&
                    strings.getAccount(follow.followable_type) === otherAccount.account &&
                    follow.followable_id === otherAccount.accountId) {
                    follow.conversation_id = data.conversation.id
                    return true
                }
            })
        } else if (data.type === 'response') {
            let index = state.userFollowers.findIndex(follow=>{
                return follow.conversation_id === data.conversationId
            })
            if (index > -1) {
                if (data.mine) {
                    state.userFollowers[index].followable_chat_status = data.response
                } else {
                    state.userFollowers[index].followedby_chat_status = data.response
                }
            }
            index = state.userFollowings.findIndex(follow=>{
                return follow.conversation_id === data.conversationId
            })
            if (index > -1) {
                if (data.mine) {
                    state.userFollowings[index].followedby_chat_status = data.response
                } else {
                    state.userFollowings[index].followable_chat_status = data.response
                }
            }
        }
    },

    ///////////////////////////////////////////////////////// accounts

    ACCOUNT_CREATE_SUCCESS(state,data){
        
        state.user.profiles.push(data.profile)
    },
    ADD_PROFILE(state,profile){
        state.user.profiles.push(profile)
    },
}

export default mutations