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
            if (!data.notSection) {                
                commit('GET_COMMENTS_SUCCESS',commentsData)
            }
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
    async searchItems({},data){
        let response = await DashboardService.searchItems(data)

        if (response.data.data) {
            return {
                status: true, 
                items: response.data.data,
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
    async sendResponse({},data){
        let response = await DashboardService.sendResponse(data)

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
            } 
            if (data.get('facilitate')) {
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
                return {status: true, data: response.data.class, action: 'update'}
            } else {
                commit('REMOVE_OWNED_CLASS',data.classId)
                if (data.hasAttribute) {
                    commit('REMOVE_CLASS',data.classId)
                }
                return {status: true, data: response.data.class, action: 'delete'}
            }
            // return {status: true}
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
                class: response.data.class,
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
    //program
    async createProgram({commit},data){
        let response = await DashboardService.createProgram(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_PROGRAM',response.data.program)
            } 
            if (data.get('facilitate')) {
                commit('ADD_NEW_PROGRAM',response.data.program)
            }
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteProgram({commit},data){
        let response = await DashboardService.deleteProgram(data)

        if (response.data.message === 'successful') {
            if (response.data.program) {
                commit('UPDATE_OWNED_PROGRAM',response.data.program)
                return {status: true, data: response.data.program, action: 'update'}
            } else {
                commit('REMOVE_OWNED_PROGRAM',data.programId)
                if (data.hasAttribute) {
                    commit('REMOVE_PROGRAM',data.programId)
                }
                return {status: true, data: response.data.program, action: 'delete'}
            }
            // return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async editProgram({commit},data){
        let response = await DashboardService.updateProgram(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_OWNED_PROGRAM',response.data.program)
            return {
                status: true,
                program: response.data.program,
                programResource: response.data.programResource
            }
        } else {
            return {status: false, response}
        }
    },
    addProgram({commit},data){
        if (data.owner) {
            commit('ADD_NEW_OWNED_PROGRAM',data.program)
        } else {
            commit('ADD_NEW_PROGRAM',data.program)
        }            
    },
    updateProgram({commit},data){
        if (data.owner) {
            commit('UPDATE_OWNED_PROGRAM',data.program)
        } else {
            commit('UPDATE_PROGRAM',data.program)
        }  
    },
    removeProgram({commit},data){
        if (data.owner) {
            commit('REMOVE_OWNED_PROGRAM',data.programId)
        } else {
            commit('REMOVE_PROGRAM',data.programId)
        }  
    },
    //course
    async createCourse({commit},data){
        let response = await DashboardService.createCourse(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_COURSE',response.data.course)
            } 
            if (data.get('facilitate')) {
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
                return {status: true, data: response.data.course, action: 'update'}
            } else {
                commit('REMOVE_OWNED_COURSE',data.courseId)
                if (data.hasAttribute) {
                    commit('REMOVE_COURSE',data.courseId)
                }
                return {status: true, data: response.data.course, action: 'delete'}
            }
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
                course: response.data.course,
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
    async createAssessment({commit, rootGetters},data){
        let response = await DashboardService.createAssessment(data)
        const currentRouteName = rootGetters.getCurrentRouteName()
        
        if (response.data.message !== 'successful') {
            return {status: false, response}
        }

        if (currentRouteName === 'dashboard') {       
            commit('ADD_NEW_ASSESSMENT',response.data.assessment)
        }

        if (currentRouteName === 'home') {       
            commit('home/ASSESSMENT_CREATE_SUCCESS', response.data.assessment, {root: true})
        }

        if (currentRouteName === 'profile') {       
            commit('profile/ASSESSMENT_CREATE_SUCCESS', response.data.assessment, {root: true})
        }

        return {status: true, assessment: response.data.assessment}
    },
    async deleteAssessment({commit, rootGetters},data){
        let response = await DashboardService.deleteAssessment(data)
        const currentRouteName = rootGetters.getCurrentRouteName()

        if (response.data.message !== 'successful') {
            return {status: false, response}
        }

        if (response.data.assessment) {
            if (currentRouteName === 'dashboard') {       
                commit('UPDATE_ASSESSMENT',response.data.assessment)
            }

            return {status: true, data: response.data.assessment, action: 'update'}
        } 

        if (currentRouteName === 'dashboard') {
            commit('REMOVE_ASSESSMENT',data.assessmentId)
        }

        if (currentRouteName === 'home') {       
            commit('home/ASSESSMENT_DELETE_SUCCESS', data, {root: true})
        }

        if (currentRouteName === 'profile') {       
            commit('profile/ASSESSMENT_DELETE_SUCCESS', data, {root: true})
        }

        return {status: true, data: response.data.assessment, action: 'delete'}
    },
    async editAssessment({commit, rootGetters},data){
        let response = await DashboardService.updateAssessment(data)
        const currentRouteName = rootGetters.getCurrentRouteName()

        if (response.data.message !== 'successful') {
            return {status: false, response}
        }
        
        if (currentRouteName === 'dashboard') {
            commit('UPDATE_ASSESSMENT',response.data.assessment)
        }

        if (currentRouteName === 'home') {       
            commit('home/ASSESSMENT_DELETE_SUCCESS', {assessment: response.data.assessment}, {root: true})
        }

        if (currentRouteName === 'profile') {       
            commit('profile/ASSESSMENT_DELETE_SUCCESS', {assessment: response.data.assessment}, {root: true})
        }

        return {
            status: true,
            assessment: response.data.assessment,
            assessmentResource: response.data.assessmentResource
        }
    },
    async answerAssessment({ commit, rootGetters }, data) {
        let response = await DashboardService.answerAssessment(data)
        const currentRouteName = rootGetters.getCurrentRouteName()

        if (response.data.message !== 'successful') {
            return { status: false, response }
        }

        if (currentRouteName === 'home' && data.addUserId) {       
            commit('home/ADD_DATA_TO_ITEM_PROPERTY', {
                item: 'assessment',
                itemId: data.assessmentId,
                data: [data.userId],
                property: 'answeredbyUserIds'
            }, {root: true})
        }

        if (currentRouteName === 'profile' && data.addUserId) {       
            commit('profile/ADD_DATA_TO_ITEM_PROPERTY', {
                item: 'assessment',
                itemId: data.assessmentId,
                data: [data.userId],
                property: 'answeredbyUserIds'
            }, {root: true})
        }

        return {status: true}
    },
    async markAssessment({ commit }, data) {
        let response = await DashboardService.markAssessment(data)

        if (response.data.message !== 'successful') {
            return { status: false, response }
        }

        return {
            status: true,
            answer: response.data.answer,
            work: response.data.work,
        }
    },
    async getWork({ commit }, data) {
        let response = await DashboardService.getWork(data)

        if (response.data.message !== 'successful') {
            return { status: false, response }
        }

        return {status: true, work: response.data.work}
    },
    async addTime({ commit, rootGetters }, main) {
        let response = await DashboardService.addTime(main.data)
        const currentRouteName = rootGetters.getCurrentRouteName()

        if (response.data.message !== 'successful') {
            return { status: false, response }
        }

        if (currentRouteName === 'home') {
            commit('home/ADD_TIMER_TO_ITEM', {
                item: main.item, timer: response.data.timer
            }, {root: true})
        }

        if (currentRouteName === 'profile') {
            commit('profile/ADD_TIMER_TO_ITEM', {
                item: main.item, timer: response.data.timer
            }, {root: true})
        }

        return {status: true}
    },
    addAssessment({commit},data){
        commit('ADD_NEW_ASSESSMENT',data.assessment)
    },
    updateAssessment({commit},data){
        commit('UPDATE_ASSESSMENT',data.assessment)
    },
    removeAssessment({commit},data){
        commit('REMOVE_ASSESSMENT',data.assessmentId)
    },
    //lesson
    async createLesson({commit},data){
        let response = await DashboardService.createLesson(data)

        if (response.data.message === 'successful') {
            commit('ADD_NEW_LESSON_TO_ITEMS',{ //for facilitating ones and owned ones
                lesson: response.data.lesson,
                items: JSON.parse(data.get('items')),
                owner: data.get('account') === data.get('owner')
            })
            commit('ADD_NEW_LESSON_TO_LESSONS',{ //for the lessons part
                lesson: response.data.lesson,
            })
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteLesson({commit},{items,data}){
        let response = await DashboardService.deleteLesson(data)

        if (response.data.message === 'successful') {
            if (response.data.lesson) {
                commit('UPDATE_ACCOUNT_LESSON',response.data.lesson)
                return {status: true, data: response.data.lesson, action: 'update'}
            } else {
                commit('REMOVE_ACCOUNT_LESSON',{items, lessonId: data.lessonId})
                if (data.hasAttribute) {
                    commit('REMOVE_LESSON',data.lessonId)
                }
                return {status: true, data: response.data.lesson, action: 'delete'}
            }
        } else {
            return {status: false, response}
        }
    },
    async editLesson({commit},data){
        let response = await DashboardService.updateLesson(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_ACCOUNT_LESSON',response.data.lesson)
            return {
                status: true,
                lesson: response.data.lesson,
                lessonResource: response.data.lessonResource,
            }
        } else {
            return {status: false, response}
        }
    },
    addLesson({commit},data){
        if (data.owner) {
            commit('ADD_NEW_OWNED_LESSON',data.lesson)
        } else {
            commit('ADD_NEW_LESSON',data.lesson)
        }            
    },
    updateLesson({commit},data){
        commit('UPDATE_ACCOUNT_LESSON',data.lesson)
    },
    removeLesson({commit},data){
        if (data.owner) {
            commit('REMOVE_OWNED_LESSON',data.lessonId)
        } else {
            commit('REMOVE_LESSON',data.lessonId)
        }  
    },
    //extracurriculum
    async createExtracurriculum({commit},data){
        let response = await DashboardService.createExtracurriculum(data)

        if (response.data.message === 'successful') {
            if (data.get('account') === data.get('owner') ||
                (data.get('account') === 'admin' && data.get('owner') === 'school')) {
                commit('ADD_NEW_OWNED_EXTRACURRICULUM',response.data.extracurriculum)
            } 
            if (data.get('facilitate')) {
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
                return {status: true, data: response.data.extracurriculum, action: 'update'}
            } else {
                commit('REMOVE_OWNED_EXTRACURRICULUM',data.extracurriculumId)
                if (data.hasAttribute) {
                    commit('REMOVE_EXTRACURRICULUM',data.extracurriculumId)
                }
                return {status: true, data: response.data.extracurriculum, action: 'delete'}
            }
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
                extracurriculum: response.data.extracurriculum,
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
    //collaboration
    async createCollaboration({commit},data){
        let response = await DashboardService.createCollaboration(data)

        if (response.data.message === 'successful') {
            commit('ADD_COLLABORATION',response.data.collaboration)
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async deleteCollaboration({commit},data){
        let response = await DashboardService.deleteCollaboration(data)

        if (response.data.message === 'successful') {
            commit('REMOVE_COLLABORATION',data.collaborationId)
            return {status: true}
        } else {
            return {status: false, response}
        }
    },
    async editCollaboration({commit},data){
        let response = await DashboardService.updateCollaboration(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_COLLABORATION',response.data.collaboration)
            return {
                status: true,
                collaboration: response.data.collaboration,
            }
        } else {
            return {status: false, response}
        }
    },
    addCollaboration({commit},data){
        commit('ADD_COLLABORATION',data.collaboration)
    },
    updateCollaboration({commit},data){
        commit('UPDATE_COLLABORATION',data.collaboration)
    },
    removeCollaboration({commit},data){
        commit('REMOVE_COLLABORATION',data.collaborationId)
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
    async getAccountSpecificItems({},data) {
        let response = await DashboardService.getAccountSpecificItems(data)

        if (response.data.data) {
            return {
                status: true,
                items: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },
    async getAccountItems({},data) {
        let response = await DashboardService.getAccountItems(data)

        if (response.data.data) {
            return {
                status: true,
                items: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },
    async getItemDetails({}, data) {
        let response = await DashboardService.getItemDetails(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                item: response.data.item,
            }
        } else {
            return {status: false, response}
        }
    },
}

export default actions