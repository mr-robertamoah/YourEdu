import DashboardService from '../../../services/dashboard.service';

const actions = {
    //status
    addStatus({commit}, data){
        commit('ADD_STATUS_MESSAGE', data)
    },
    clearStatus({commit}){
        commit('CLEAR_STATUS_MESSAGE')
    },
    //likes
    newLike({commit}, like){
        commit('NEW_LIKE', like)
    },
    removeLike({commit}, likeInfo){
        commit('REMOVE_LIKE', likeInfo)
    },
    //comments
    newComment({commit}, comment){
        commit('NEW_COMMENT', comment)
    },
    removeComment({commit}, commentInfo){
        commit('REMOVE_COMMENT', commentInfo)
    },
    updateComment({commit}, commentInfo){
        commit('UPDATE_COMMENT', commentInfo)
    },
    addAccountDetails({commit},data){
        commit('ADD_ACCOUNT_DETAILS',data)
    },
    async getSectionItemComments({commit},data){
        let response = await DashboardService.getSectionItemComments(data)

        if (response.data.data) {
            let commentsData = {
                status: true,
                comments: response.data.data,
                next: response.data.links.next,
                nextPage: data.nextPage
            }
            commit('GET_COMMENTS_SUCCESS',commentsData)
            return commentsData
        } else {
            return {status: false, response}
        }
    },
    //users
    async fetchUsers({commit},data){
        let response = await DashboardService.fetchUsers(data)

        if (response.data.data) {
            let usersData = {
                status: true,
                users: response.data.data,
                next: response.data.links.next,
                nextPage: data.nextPage
            }
            commit('GET_USERS_SUCCESS',usersData)
            return usersData
        } else {
            return {status: false, response}
        }
    },
    //accounts
    async fetchAccounts({commit},data){
        let response = await DashboardService.fetchAccounts(data)

        if (response.data.data) {
            let accountsData = {
                status: true,
                accounts: response.data.data,
                next: response.data.links.next,
                nextPage: data.nextPage
            }
            commit('GET_ACCOUNTS_SUCCESS',accountsData)
            return accountsData
        } else {
            return {status: false, response}
        }
    },
    async fetchAccountActivities({commit},data){
        let response = await DashboardService.fetchAccountActivities(data)

        if (response.data.data) {
            let accountsData = {
                status: true,
                activities: response.data.data,
                next: response.data.links.next,
                nextPage: data.nextPage
            }
            commit('GET_ACCOUNT_ACTIVITIES_SUCCESS',accountsData)
            return accountsData
        } else {
            return {status: false, response}
        }
    },
    clearAccountActivities({commit}){
        commit('CLEAR_ACCOUNT_ACTIVITIES')
    },
    async fetchAdmins({commit},data){
        let response = await DashboardService.fetchAdmins(data)

        if (response.data.data) {
            let adminsData = {
                status: true,
                admins: response.data.data,
                next: response.data.links.next,
                nextPage: data.nextPage
            }
            commit('GET_ADMINS_SUCCESS',adminsData)
            return adminsData
        } else {
            return {status: false, response}
        }
    },
    async fetchItem({commit},data){
        let response = await DashboardService.fetchItem(data)

        if (response.data.message === 'successful') {

            return {status: true, item: response.data.item}
        } else {
            return {status: false, response}
        }
    },
    async createAcademicYear({commit},data){
        let response = await DashboardService.createAcademicYear(data)

        if (response.data.message === 'successful') {
            commit('ADD_ACCOUNT_DETAILS',{
                data: response.data.academicYear,
                what: 'academicYear',
                account: 'school',
                accountId: data.schoolId
            })
            return {    
                status: true, 
            }
        } else {
            return {status: false, response}
        }
    },
    async searchAccounts({},data){
        let response = await DashboardService.searchAccounts(data)

        if (response.data.data) {
            return {
                status: true, 
                accounts: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },
    async sendRequest({},data){
        let response = await DashboardService.sendRequest(data)

        if (response.data.message === 'successful') {
            return {
                status: true, 
            }
        } else {
            return {status: false, response}
        }
    },
    async getDashboardAccountDetails({commit},data){
        let response = await DashboardService.getDashboardAccountDetails(data)

        if (response.data.message === 'successful') {
            commit('DASHBOARD_ACCOUNT_SUCCESS',{
                details: response.data.accountDetails,
                data
            })
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    //class
    async createClass({commit},data){
        let response = await DashboardService.createClass(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_CLASS',response.data.class)
            } else {
                commit('ADD_NEW_CLASS',response.data.class)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteClass({commit},data){
        let response = await DashboardService.deleteClass(data)

        if (response.data.message === 'successful') {
            if (response.data.class) {
                commit('UPDATE_OWNED_CLASS',response.data.class)
            } else {
                commit('REMOVE_OWNED_CLASS',data.classId)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async editClass({commit},data){
        let response = await DashboardService.updateClass(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_OWNED_CLASS',response.data.class)
            return {
                status: true,
                classResource: response.data.classResource
            }
        } else {
            return {status: false, response}
        }
    },
    addClass({commit},data){
        if (data.owner) {
            commit('ADD_NEW_OWNED_CLASS',data.class)
        } else {
            commit('ADD_NEW_CLASS',data.class)
        }            
    },
    updateClass({commit},data){
        if (data.owner) {
            commit('UPDATE_OWNED_CLASS',data.class)
        } else {
            commit('UPDATE_CLASS',data.class)
        }  
    },
    removeClass({commit},data){
        if (data.owner) {
            commit('REMOVE_OWNED_CLASS',data.classId)
        } else {
            commit('REMOVE_CLASS',data.classId)
        }  
    },
    //course
    async createCourse({commit},data){
        let response = await DashboardService.createCourse(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_COURSE',response.data.course)
            } else {
                commit('ADD_NEW_COURSE',response.data.course)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteCourse({commit},data){
        let response = await DashboardService.deleteCourse(data)

        if (response.data.message === 'successful') {
            if (response.data.course) {
                commit('UPDATE_OWNED_COURSE',response.data.course)
            } else {
                commit('REMOVE_OWNED_COURSE',data.courseId)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async editCourse({commit},data){
        let response = await DashboardService.updateCourse(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_OWNED_COURSE',response.data.course)
            return {
                status: true,
                courseResource: response.data.courseResource
            }
        } else {
            return {status: false, response}
        }
    },
    addCourse({commit},data){
        if (data.owner) {
            commit('ADD_NEW_OWNED_COURSE',data.course)
        } else {
            commit('ADD_NEW_COURSE',data.course)
        }            
    },
    updateCourse({commit},data){
        if (data.owner) {
            commit('UPDATE_OWNED_COURSE',data.course)
        } else {
            commit('UPDATE_COURSE',data.course)
        }  
    },
    removeCourse({commit},data){
        if (data.owner) {
            commit('REMOVE_OWNED_COURSE',data.courseId)
        } else {
            commit('REMOVE_COURSE',data.courseId)
        }  
    },
    //extracurriculum
    async createExtracurriculum({commit},data){
        let response = await DashboardService.createExtracurriculum(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_EXTRACURRICULUM',response.data.extracurriculum)
            } else {
                commit('ADD_NEW_EXTRACURRICULUM',response.data.extracurriculum)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteExtracurriculum({commit},data){
        let response = await DashboardService.deleteExtracurriculum(data)

        if (response.data.message === 'successful') {
            if (response.data.extracurriculum) {
                commit('UPDATE_OWNED_EXTRACURRICULUM',response.data.extracurriculum)
            } else {
                commit('REMOVE_OWNED_EXTRACURRICULUM',data.extracurriculumId)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async editExtracurriculum({commit},data){
        let response = await DashboardService.updateExtracurriculum(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_OWNED_EXTRACURRICULUM',response.data.extracurriculum)
            return {
                status: true,
                extracurriculumResource: response.data.extracurriculumResource
            }
        } else {
            return {status: false, response}
        }
    },
    addExtracurriculum({commit},data){
        if (data.owner) {
            commit('ADD_NEW_OWNED_EXTRACURRICULUM',data.extracurriculum)
        } else {
            commit('ADD_NEW_EXTRACURRICULUM',data.extracurriculum)
        }            
    },
    updateExtracurriculum({commit},data){
        if (data.owner) {
            commit('UPDATE_OWNED_EXTRACURRICULUM',data.extracurriculum)
        } else {
            commit('UPDATE_EXTRACURRICULUM',data.extracurriculum)
        }  
    },
    removeExtracurriculum({commit},data){
        if (data.owner) {
            commit('REMOVE_OWNED_EXTRACURRICULUM',data.extracurriculumId)
        } else {
            commit('REMOVE_EXTRACURRICULUM',data.extracurriculumId)
        }  
    },
    //
    async createAccountAttachments({},data){
        let response = await DashboardService.createAccountAttachments(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
            }
        } else {
            return {status: false, response}
        }
    },
    async deleteAccountAttachments({commit},data){
        let response = await DashboardService.deleteAccountAttachments(data)

        if (response.data.message === 'successful') {
            if (!data.mainSection) {
                commit('REMOVE_ACCOUNT_ATTACHMENT',data)
            }
            return {
                status: true,
            }
        } else {
            return {status: false, response}
        }
    },
    addAccountAttachments({commit},data) {
        commit('ADD_ACCOUNT_ATTACHMENT',data)
    },
    ////
    async getSpecificAccountDetails({commit},data){
        let response = await DashboardService.getDashboardAccountDetails(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                accountDetails: response.data.accountDetails,
            }
        } else {
            return {status: false, response}
        }
    },
    async sendRequestMessage({},data){
        let response = await DashboardService.sendRequestMessage(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                message: response.data.requestMessage,
            }
        } else {
            return {status: false, response}
        }
    },
    async deleteRequestMessage({},data){
        let response = await DashboardService.deleteRequestMessage(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
            }
        } else {
            return {status: false, response}
        }
    },
    async getRequestMessages({},data){
        let response = await DashboardService.getRequestMessages(data)

        if (response.data.data) {
            return {
                status: true,
                messages: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },
    async getSectionItemData({},data){
        let response = await DashboardService.getSectionItemData(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                mainSectionData: response.data.mainSectionData,
            }
        } else {
            return {status: false, response}
        }
    },
    async getAccountRequests({},data){
        let response = await DashboardService.getAccountRequests(data)

        if (response.data.data) {
            return {
                status: true,
                requests: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },
    async banUser({commit},data){
        let response = await DashboardService.banUser(data)

        if (response.data.message === 'successful') {
            commit('CHANGE_BAN_STATUS', response.data.account)
            return {
                status: true,
            }
        } else {
            return {status: false, response}
        }
    },
    async getAccountSpecificItem({},data) {
        let response = await DashboardService.getAccountSpecificItem(data)

        if (response.data.data) {
            return {
                status: true,
                items: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    }
}

export default actions