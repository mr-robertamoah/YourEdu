import actions from './dashboard/dashboard.actions';
import mutations from './dashboard/dashboard.mutations';


const dashboard = {
    namespaced: true,
    state: {
        currentAccount: {},
        accountDetails: {},
        mainSectionComments: [],
        users: [],
        accounts: [],
        activities: [],
        admins: [],
        statusMessage: '',
    },

    mutations: mutations,

    actions: actions,

    getters: {

        getAccountDetails(state){
            return state.accountDetails
        },
        getCurrentAccount(state){
            return state.currentAccount
        },
        getMainSectionComments(state){
            return state.mainSectionComments
        },
        getUsers(state){
            return state.users
        },
        getAccounts(state){
            return state.accounts
        },
        getActivities(state){
            return state.activities
        },
        getAdmins(state){
            return state.admins
        },
        getStatus(state){
            return state.statusMessage
        },
    },
}

export default dashboard