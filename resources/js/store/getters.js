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

    getUserAge(state){
        return state.user ? state.user.age : null
    },

    getUser(state){
        return state.user ? state.user : null
    },

    getUserId(state){
        return state.user ? state.user.id : null
    },
    
    isSuperadmin(state){
        return state.user ? state.user.is_superadmin : false
    },
    
    getLoading(state){
        return state.loading
    },
    
    isGroupadmin(state){
        return state.user ? state.user.is_groupadmin : false
    },
    
    isClassadmin(state){
        return state.user ? state.user.is_classadmin : false
    },
    
    isSchooladmin(state){
        return state.user ? state.user.is_schooladmin : false
    },
    
    isLearner(state){
        return state.user ? state.user.is_learner : false
    },
    
    isParent(state){
        return state.user ? state.user.is_parent : false
    },
    
    isFacilitator(state){
        return state.user ? state.user.is_facilitator : false
    },
    
    hasProfessionals(state){
        return state.user ? state.user.has_professionals : false
    },
    
    hasSchools(state){
        return state.user ? state.user.has_schools : false
    },
    
    getUserFollowRequest(state){
        return state.userFollowRequest ? state.userFollowRequest : []
    },
    
    getProfiles(state){ // this gives the available profiles of the user
        // return state.user ? state.user.has_schools : false
        let profilesArray = []
        let computedArray = []

        if (state.user) {
            profilesArray = state.user.owned_profiles
        } else {
            return null
        }

        if (profilesArray) {
            computedArray = profilesArray.map(el=>{
                return {
                    name: el.profile_name ? el.profile_name : 'no name',
                    url: el.profile_url ? el.profile_url: '',
                    profile: el.profile ? el.profile : '',
                    params: {
                        account: el.account_type,
                        accountId: el.account_id,
                    }
                }
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
    // getParent(state){
    //     return state.user ? state.user.parent: null
    // },

    // getLearner(state){
    //     return state.user ? state.user.learner : null
    // },

    // getSchools(state){
    //     return state.user ? state.user.schools : null
    // },

    // getAdmins(state){
    //     return state.user ? state.user.admins : null
    // },

    // getFacilitator(state){
    //     return state.user ? state.user.facilitator : null
    // },

    // getProfessionals(state){
    //     return state.user ? state.user.professionals : null
    // },
}

export default getters