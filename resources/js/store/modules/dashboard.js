import DashboadService from "../../services/dashboard.service"
import { strings } from "../../services/helpers"
import mutations from "../mutations"
import state from "../state"


const dashboard = {
    namespaced: true,
    state: {
        currentAccount: {},
        accountDetails: {},
        mainSectionComments: [],
        users: [],
        admins: [],
    },

    mutations: {

        //users
        GET_USERS_SUCCESS(state,data){
            if (data.nextPage === 1) {
                state.users = data.users
            } else {
                state.users.push(...data.users)
            }
        },
        //admins
        GET_ADMINS_SUCCESS(state,data){
            if (data.nextPage === 1) {
                state.admins = data.admins
            } else {
                state.admins.push(...data.admins)
            }
        },
        CHANGE_BAN_STATUS(state,account){
            let index 
            if (account.username) {
                index= state.users.findIndex(user=>{
                    return user.id === account.id
                })
                if (index > -1) {
                    state.users.splice(index,1,account)
                }
            }
        },
        DASHBOARD_ACCOUNT_SUCCESS(state,data){
            state.accountDetails = data.details
            state.currentAccount = data.data
        },
        //class
        ADD_NEW_CLASS(state,data){
            state.accountDetails.classes.push(data)
        },
        ADD_NEW_OWNED_CLASS(state,data){
            state.accountDetails.ownedClasses.push(data)
        },
        UPDATE_CLASS(state,data){
            let index = state.accountDetails.classes.findIndex(c=>{
                return c.id === data.id
            })
            if (index > -1) {
                state.accountDetails.classes.splice(index,1,data)
            }
        },
        UPDATE_OWNED_CLASS(state,data){
            let index = state.accountDetails.ownedClasses.findIndex(c=>{
                return c.id === data.id
            })
            if (index > -1) {
                state.accountDetails.ownedClasses.splice(index,1,data)
            }
        },
        REMOVE_CLASS(state,data){
            let index = state.accountDetails.classes.findIndex(c=>{
                return c.id === data.id
            })
            if (index > -1) {
                state.accountDetails.classes.splice(index,1)
            }
        },
        REMOVE_OWNED_CLASS(state,data){
            let index = state.accountDetails.ownedClasses.findIndex(c=>{
                return c.id === data.id
            })
            if (index > -1) {
                state.accountDetails.ownedClasses.splice(index,1)
            }
        },
        SCHOOL_ADD_ACCOUNTS(state,data){
            if (data.accountOne.account === 'learner') {
                state.accountDetails.learners.push(data.accountOne)
            } else if (data.accountOne.account === 'parent') {
                state.accountDetails.parents.push(data.accountOne)
            }

            if (data.accountTwo) {
                state.accountDetails.parents.push(data.accountTwo)
            }
        },
        ADD_ACCOUNT_DETAILS(state,data){
            if (data.account === state.accountDetails.account && 
                data.accountId === state.accountDetails.accountId) {
                if (data.what === 'admin') {
                    state.accountDetails.admins.push(data.data)
                } else if (data.what === 'facilitator') {
                    state.accountDetails.facilitators.push(data.data)
                } else if (data.what === 'school') {
                    state.accountDetails.schools.push(data.data)
                } else if (data.what === 'academicYear') {
                    state.accountDetails.academicYears.push(data.data)
                }
            }
        },
        //comments
        COMMENT_SUCCESS(state, data){
            let commentIndex 
            if (!data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                state.mainSectionComments.unshift(data.comment)
            } else if (data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.comment.commentable_id
                })
                if (commentIndex > -1) {
                    state.mainSectionComments[commentIndex].comments += 1
                }
            }
        },
        COMMENT_UPDATE_SUCCESS(state,data){
            let commentIndex
            if (!data.comment.commentable_type.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.comment.id
                })
                if (commentIndex > -1) {
                    state.mainSectionComments.splice(commentIndex,1,data.comment)
                }
            }
        },
        COMMENT_DELETE_SUCCESS(state,data){
            let commentIndex
            if (!data.owner.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.commentId
                })
                if (commentIndex > -1) {
                    state.mainSectionComments.splice(commentIndex,1)
                }
            } else if (data.owner.toLocaleLowerCase().includes('comment')) {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.ownerId
                })
                if (commentIndex > -1) {
                    state.mainSectionComments[commentIndex].comments -= 1
                }
            }
        },
        GET_COMMENTS_SUCCESS(state,data){
            if (data.nextPage === 1) {
                state.mainSectionComments = data.comments
            } else {
                state.mainSectionComments.push(...data.comments)
            }
        },
        NEW_COMMENT(state, data){ //only for posts
            let itemId = Number(data.itemId),
                index
            if (data.item === 'comment') {
                index = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === itemId
                })
                if (index > -1) {
                    state.mainSectionComments[index].comments += 1
                }
            } else if (data.item !== 'comment') {
                state.mainSectionComments.unshift(data.comment)
            }
        },
        UPDATE_COMMENT(state, data){
            let itemId = Number(data.itemId),
                commentIndex
            if (data.item === 'comment') {
                
            } else if (data.item !== 'comment') {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === itemId
                })
                if (commentIndex > -1) {
                    state.mainSectionComments.splice(commentIndex,1,data.comment)
                }
            }
        },
        REMOVE_COMMENT(state, data){
            let itemId = Number(data.itemId),
                commentId = Number(data.commentId),
                commentIndex = null
            if (data.item === 'comment') {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === commentId
                })
                if (commentIndex > -1) {
                    state.mainSectionComments[commentIndex] -= 1
                }
            } else if (data.item !== 'comment') {
                commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === commentId
                })
                if (commentIndex > -1) {
                    state.mainSectionComments.splice(commentIndex,1)
                }
            }
        },
        //saves
        SAVE_CREATE_SUCCESS(state,data){

        },
        SAVE_DELETE_SUCCESS(state,data){

        },
        //flags
        FLAG_DELETE_SUCCESS(state,data){

        },
        FLAG_CREATE_SUCCESS(state,data){

        },
        //attachments
        ATTACHMENT_DELETE_SUCCESS(state,data){

        },
        ATTACHMENT_CREATE_SUCCESS(state,data){

        },
        //likes
        LIKE_DELETE_SUCCESS(state,data){
            let likeIndex
            if (data.item === 'comment') {
                let commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.itemId
                })
                if (commentIndex > -1) {
                    likeIndex = state.mainSectionComments[commentIndex].likes.findIndex(like=>{
                        return like.id === data.likeId
                    })

                    if (likeIndex > -1) {
                        state.mainSectionComments[commentIndex].likes.splice(likeIndex,1)
                        return
                    }
                }
            }
        },
        LIKE_CREATE_SUCCESS(state,data){
            let likeIndex
            if (data.item === 'comment') {
                let commentIndex = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === data.itemId
                })

                if (commentIndex > -1) {
                    likeIndex = state.mainSectionComments[commentIndex].likes.findIndex(like=>{
                        return like.id === data.like.id
                    })
                    if (likeIndex > -1) {
                        return
                    }
                    state.mainSectionComments[commentIndex].likes.push(data.like)
                }
            } 
        },
        NEW_LIKE(state,data){
            let index = null,
                likeIndex = null,
                itemId = Number(data.itemId)
            if (data.item === 'comment') {
                index = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === itemId
                })
                if (index > -1) {
                    likeIndex = state.mainSectionComments[index].likes.findIndex(l=>{
                        return l.id === data.like.id
                    })
                    if (likeIndex === -1) {
                        state.mainSectionComments[index].likes.push(data.like)
                    }
                }
            }
        },
        REMOVE_LIKE(state,data){
            let index = null,
                likeIndex = null,
                itemId = Number(data.itemId),
                likeId = Number(data.likeId)
            if (data.item === 'comment') {
                index = state.mainSectionComments.findIndex(comment=>{
                    return comment.id === itemId
                })
                if (index > -1) {
                    likeIndex = state.mainSectionComments[index].likes.findIndex(l=>{
                        return l.id === likeId
                    })
                    if (likeIndex > -1) {
                        state.mainSectionComments[index].likes.splice(likeIndex,1)
                    }
                }
            }
        },



    },

    actions: {
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
            let response = await DashboadService.getSectionItemComments(data)

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
            let response = await DashboadService.fetchUsers(data)

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
        async fetchAdmins({commit},data){
            let response = await DashboadService.fetchAdmins(data)

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
        async createAcademicYear({commit},data){
            let response = await DashboadService.createAcademicYear(data)

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
            let response = await DashboadService.searchAccounts(data)

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
            let response = await DashboadService.sendRequest(data)

            if (response.data.message === 'successful') {
                return {
                    status: true, 
                }
            } else {
                return {status: false, response}
            }
        },
        async getDashboardAccountDetails({commit},data){
            let response = await DashboadService.getDashboardAccountDetails(data)

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
            let response = await DashboadService.createClass(data)

            if (response.data.message === 'successful') {
                if (data.account === 'facilitator' && data.owner !== 'facilitator') {
                    commit('ADD_NEW_CLASS',response.data.class)
                } else {
                    commit('ADD_NEW_OWNED_CLASS',response.data.class)
                }
                return {status: true}
            } else {
                return {status: false, response}
            }
        },
        async deleteClass({commit},data){
            let response = await DashboadService.deleteClass(data)

            if (response.data.message === 'successful') {
                commit('REMOVE_OWNED_CLASS',response.data.class)
                return {status: true}
            } else {
                return {status: false, response}
            }
        },
        async editClass({commit},data){
            let response = await DashboadService.updateClass(data)

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
        async getSpecificAccountDetails({commit},data){
            let response = await DashboadService.getDashboardAccountDetails(data)

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
            let response = await DashboadService.sendRequestMessage(data)

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
            let response = await DashboadService.deleteRequestMessage(data)

            if (response.data.message === 'successful') {
                return {
                    status: true,
                }
            } else {
                return {status: false, response}
            }
        },
        async getRequestMessages({},data){
            let response = await DashboadService.getRequestMessages(data)

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
            let response = await DashboadService.getSectionItemData(data)

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
            let response = await DashboadService.getAccountRequests(data)

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
            let response = await DashboadService.banUser(data)

            if (response.data.message === 'successful') {
                commit('CHANGE_BAN_STATUS', response.data.account)
                return {
                    status: true,
                }
            } else {
                return {status: false, response}
            }
        },
    },

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
        getAdmins(state){
            return state.admins
        },
    },
}

export default dashboard