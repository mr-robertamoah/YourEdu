const { default: profile } = require("./modules/profile")

const getters = {
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
    
    getLoading(state){
        return state.loading
    },
    
    getUserFollowRequest(state){
        return state.userFollowRequest ? state.userFollowRequest : []
    },
    
    getProfiles(state){ // this gives the available profiles of the user
        // return state.user ? state.user.has_schools : false
        let profilesArray = []
        let computedArray = []

        if (state.user) {
            profilesArray = state.user.profiles
        } else {
            return null
        }

        if (profilesArray) {
            computedArray = profilesArray.map(el=>{
                let profile =  {
                    name: el.profile_name ? el.profile_name : 'no name',
                    url: el.profile_url ? el.profile_url: '',
                    profile: el.profile ? el.profile : '',
                    params: {
                        account: el.account_type,
                        accountId: el.account_id,
                    }
                }

                if (profile.params.account === 'school') {
                    profile.admin = el.admin
                }

                return profile
            })

            return computedArray
        } else {
            return null
        }
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
            return profile.account_type === 'parent'
        }) > -1 
    },

    isLearner(state){
        return state.user.profiles.findIndex(profile=>{
            return profile.account_type === 'learner'
        }) > -1 
    },

    isFacilitator(state){
        return state.user.profiles.findIndex(profile=>{
            return profile.account_type === 'facilitator'
        }) > -1 
    },

    professionalsCount(state){
        return state.user.profiles.filter(profile=>{
            return profile.account_type === 'professional'
        }).length
    },

    schoolsCount(state){
        return state.user.profiles.filter(profile=>{
            return profile.account_type === 'school' && profile.userId === state.user.id
        }).length
    },
}

export default getters