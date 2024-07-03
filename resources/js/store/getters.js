import settings from "../settings"

const getters = {
    getCurrentRouteName(state){
        return state.currentRouteName
    },
    
    authenticating(state){
        return state.authenticating
    },

    authenticatingUser(state){
        return state.authenticatingUser
    },

    authenticatingErrorCode(state){
        return state.authenticatingErrorCode
    },

    authenticatingErrorMessage(state){
        return state.authenticatingErrorMessage
    },

    getValidationErrors(state){
        return state.validationErrors
    },

    getUserUsername(state){
        return state.user ? state.user.username : null
    },

    getAccessToken(state){
        return state.accessToken ? state.accessToken : null
    },

    getUserAge(state){
        return state.user ? state.user.age : null
    },

    getUser(state){
        return state.user ? state.user : null
    },

    getUserFollowings(state){
        return state.userFollowings ? state.userFollowings : []
    },

    getUserFollowers(state){
        return state.userFollowers ? state.userFollowers : []
    },

    getUserId(state){
        return state.user ? state.user.id : null
    },

    isAdult(state) {
        return state.user?.age >= settings.minimumAdultAge
    },
    
    getLoading(state){
        return state.loading
    },
    
    getUserFollowRequest(state){
        return state.userFollowRequest ? state.userFollowRequest : []
    },
    
    getProfiles(state){

        if (state.user) {
            return state.user.profiles
        }
        
        return null
    },
    
    getActiveProfile(state,getters){
        return state.profile.activeProfile ? state.profile.activeProfile : 
            getters.getProfiles ? getters.getProfiles[0] : null
    },
    getLoggedin(state){
        return state.loggedin
    },
    isParent(state){
        return state.user.profiles.findIndex(profile=>{
            return profile.account === 'parent'
        }) > -1 
    },

    isLearner(state){
        return state.user.profiles.findIndex(profile=>{
            return profile.account === 'learner'
        }) > -1 
    },

    isFacilitator(state){
        return state.user.profiles.findIndex(profile=>{
            return profile.account === 'facilitator'
        }) > -1 
    },

    professionalsCount(state){
        return state.user.profiles.filter(profile=>{
            return profile.account === 'professional'
        }).length
    },

    schoolsCount(state){
        return state.user.profiles.filter(profile=>{
            return profile.account === 'school' && profile.userId === state.user.id
        }).length
    },
}

export default getters