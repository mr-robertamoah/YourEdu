import { ProfileService } from "../../../services/profile.service"

const actions = {

    ///////////////////////////////////// follows
    async follow({commit}, data){
        console.log('data in profile',data)
        let response = await ProfileService.followCreate(data)

        if (response.data.message === 'successful') {
            commit('FOLLOW_SUCCESS',response.data)
            commit('FOLLOW_SUCCESS',response.data.following,{root:true})
            return {status: true, follow: response.data.follow}
        }else {
            commit('PROFILE_FAILURE','following was unsuccessul')
            return {status: false}
        }
    },
    async unfollow({commit}, data){
        
        let response = await ProfileService.followDelete(data)

        if (response.data.message === 'successful') {
            commit('UNFOLLOW_SUCCESS',data)
            return 'successful'
        }else {
            commit('PROFILE_FAILURE','unfollowing was unsuccessul')
            return 'unsuccessful'
        }
    },

    ///////////////////////////////////////////// profile

    async deleteMedia({commit},data){
        let response = await ProfileService.profileMediaDelete(data)

        if (response.data.message === 'successful') {
            return 'successful'
        }else {
            return 'unsuccessful'
        }
    },
    async changeMedia({commit},data){
        let response = await ProfileService.profileMediaChange(data)

        if (response.data.message === 'successful') {
            commit('PUBLIC_MEDIA_SUCCESS',{main: response.data,data})
            return 'successful'
        }else {
            return 'unsuccessful'
        }
    },
    setActiveProfile({commit,rootGetters},data){
        let computedArray = []

        if (! rootGetters.getProfiles || ! rootGetters.getProfiles.length) {
            
            commit('SET_ACTIVE_PROFILE', null)
            return
        }

        computedArray = rootGetters.getProfiles.filter(profile => {
            return profile.accountId === data?.accountId && 
                profile.account === data?.account
        })

        if (computedArray.length) {
            commit('SET_ACTIVE_PROFILE',computedArray[0])
            return
        }
        
        commit('SET_ACTIVE_PROFILE',rootGetters.getProfiles[0])

    },
    async updateProfile({commit},data){

        let response = await ProfileService.profileUpdate(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_PROFILE_SUCCESS',response.data,data)
        }else {
            commit('PROFILE_FAILURE','update was unsuccessul')
        }
    },
    async updateProfilePic({commit},data){

        let response = await ProfileService.profilePicUpdate(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_PROFILE_PIC_SUCCESS',response.data)
            return response.data.message
        }else {
            return response.data.message
        }
    },
    async uploadFile({commit},data){

        let response = await ProfileService.profileFileUpload(data)

        if (response.data.message === 'successful') {
            commit('UPLOAD_FILE_SUCCESS',response.data)
            return response.data.message
        }else {
            return response.data.message
        }
    },
    clearMedia({commit}){
        commit("CLEAR_MEDIA")
    },
    async getMedia({commit},data){
        let response = await ProfileService.profileMediaGet(data)

        if (response.data.data) {
            return response.data
        }else {
            return 'unsuccessful'
        }
    },
    async getPrivateMedia({commit},data){
        let response = await ProfileService.profilePrivateMediaGet(data)

        if (response.data.data) {
            return response.data
        }else {
            return 'unsuccessful'
        }
    },
    async addInfo({commit},data){

        let response = await ProfileService.profileAddInfo(data)

        if (response.data.message === 'successful') {
            commit('ADD_INFO_SUCCESS',response.data,data)
            return 'successful'
        }else {
            return response.data.errors
        }
    },
    async markInfo({commit},data){

        let response = await ProfileService.profileMarkInfo(data)

        if (response.data.message === 'successful') {
            commit('MARK_INFO_SUCCESS',data)
            return 'successful'
        }else {
            return 'unsuccessful'
        }
    },
    async removeInfo({commit},data){

        let response = await ProfileService.profileDeleteInfo(data)

        if (response.data.message === 'successful') {
            commit('REMOVE_INFO_SUCCESS',data)
            return 'successful'
        }else {
            return 'unsuccessful'
        }
    },

    ///////////////////////////////////////////////////

    clearMsg({commit}){
        commit('CLEAR_PROFILE_MSG')
    },

    ////////////////////////////////////// chat

    async sendMessageResponse({commit},data){

        let response = await ProfileService.sendMessageResponse(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_USER_FOLLOWS',{
                type: 'response',
                conversationId:response.data.conversation.id,
                response: data.data.response,
                mine: true
            }, {root:true})
            return {status: true, conversation: response.data.conversation}
        }else {
            return {status: false, message: response}
        }
    },
    async blockConversation({commit},data){

        let response = await ProfileService.blockConversation(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_USER_FOLLOWS',{
                type: 'response',
                conversationId:response.data.conversation.id,
                response: 'BLOCK',
                mine: true
            }, {root:true})
            return {status: true, conversation: response.data.conversation}
        }else {
            return {status: false, message: response}
        }
    },
    async unblockConversation({commit},data){

        let response = await ProfileService.unblockConversation(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_USER_FOLLOWS',{
                type: 'response',
                conversationId:response.data.conversation.id,
                response: 'ACCEPT',
                mine: true
            }, {root:true})
            return {status: true, conversation: response.data.conversation}
        }else {
            return {status: false, message: response}
        }
    },
    async sendChatMessage({commit},data){

        let response = await ProfileService.sendChatMessage(data)

        if (response.data.message === 'successful') {
            return {status: true, chatMessage: response.data.chatMessage}
        }else {
            return {status: false, message: response}
        }
    },
    async sendChatAnswer({commit},data){

        let response = await ProfileService.sendChatAnswer(data)

        if (response.data.message === 'successful') {
            return {status: true, chatQuestion: response.data.chatQuestion}
        }else {
            return {status: false, message: response}
        }
    },
    async sendChatMark({commit},data){

        let response = await ProfileService.sendChatMark(data)

        if (response.data.message === 'successful') {
            return {status: true, chatAnswer: response.data.chatAnswer}
        }else {
            return {status: false, message: response}
        }
    },
    async sendChatQuestion({commit},data){

        let response = await ProfileService.sendChatQuestion(data)

        if (response.data.message === 'successful') {
            return {status: true, chatQuestion: response.data.chatQuestion}
        }else {
            return {status: false, message: response}
        }
    },
    async deleteChatQuestion({commit},data){

        let response = await ProfileService.deleteChatItem(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                chatQuestion: response.data.chatItem
            }
        }else {
            return {status: false, message: response}
        }
    },
    async deleteChatAnswer({commit},data){

        let response = await ProfileService.deleteChatItem(data)

        if (response.data.message === 'successful') {
            return  {
                status: true,
                chatAnswer: response.data.chatItem
            }
        }else {
            return {status: false, message: response}
        }
    },
    async deleteChatMessage({commit},data){

        let response = await ProfileService.deleteChatItem(data)

        if (response.data.message === 'successful') {
            return  {
                status: true,
                chatMessage: response.data.chatItem
            }
        }else {
            return {status: false, message: response}
        }
    },
    async updateChatItemStatus({commit},data){
        let response = await ProfileService.updateChatItemStatus(data)

        if (response.data.message === 'successful') {
            return  {
                status: true,
                chatItem: response.data.chatItem
            }
        }else {
            return {status: false, message: response}
        }
    },
    async getChatMessages({commit},data){

        let response = await ProfileService.getChatMessages(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response}
        }
    },
    async getChatConversations({commit},data){

        let response = await ProfileService.getChatConversations(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response}
        }
    },
    async getBlockedConversations({commit},data){

        let response = await ProfileService.getBlockedConversations(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response}
        }
    },
    async getPendingConversations({commit},data){

        let response = await ProfileService.getPendingConversations(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response}
        }
    },
    async createConversation({commit},data){

        let response = await ProfileService.createConversation(data)

        if (response.data.message === 'successful') {
            commit('UPDATE_USER_FOLLOWS',{
                type: 'conversation',
                conversation:response.data.conversation
            }, {root:true})
            return {
                status: true, 
                conversation: response.data.conversation
            }
        }else {
            return {status: false, message: response}
        }
    },

    ////////////////////////////////////// saves

    async createSave({commit},data){
        let response = await ProfileService.saveCreate(data)
        if (response.data.message === 'successful') {
            data['save'] = response.data.save
            if (data.where === 'profile') {
                commit('SAVE_CREATE_SUCCESS', data)
            } else if (data.where === 'home') {
                commit('home/SAVE_CREATE_SUCCESS', data,{root: true})
            } else if (data.where === 'dashboard') {
                commit('dashboard/SAVE_CREATE_SUCCESS', data,{root: true})
            }
            return {status: true, save: response.data.save}
        }else {
            commit('PROFILE_FAILURE','saving unsuccessful')
            return {status: false, message: 'unsuccessful'}
        }
    },
    async deleteSave({commit},data){
        let response = await ProfileService.saveDelete(data)
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('SAVE_DELETE_SUCCESS', data)
            } else if (data.where === 'home') {
                commit('home/SAVE_DELETE_SUCCESS', data, {root: true})
            } else if (data.where === 'dashbaord') {
                commit('dashbaord/SAVE_DELETE_SUCCESS', data, {root: true})
            }
            return {status: true, message: 'successful'}
        }else {
            commit('PROFILE_FAILURE','saving unsuccessful')
            return {status: false, message: 'unsuccessful'}
        }
    },

    ////////////////////////////////////// likes

    async createLike({commit},data){
        let response = await ProfileService.likeCreate(data)
        if (response.data.message === 'successful') {
            data['like'] = response.data.like
            if (data.where === 'profile') {
                commit('LIKE_CREATE_SUCCESS', data)
            } else if (data.where === 'dashboard') {
                commit('dashboard/LIKE_CREATE_SUCCESS', data, {root: true})
            } else if (data.where === 'home') {
                commit('home/LIKE_CREATE_SUCCESS', data, {root: true})
            }
            return response.data.like
        }else {
            commit('PROFILE_FAILURE','liking unsuccessful')
            return 'unsuccessful'
        }
    },
    async deleteLike({commit, rootGetters},data){
        let response = await ProfileService.likeDelete(data)
        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','liking unsuccessful')
            return 'unsuccessful'
        }

        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'profile') {
            commit('LIKE_DELETE_SUCCESS', data)
        }
        
        if (currentRouteName === 'dashboard') {
            commit('dashboard/LIKE_DELETE_SUCCESS', data, {root: true})
        }
        
        if (currentRouteName === 'home') {
            commit('home/LIKE_DELETE_SUCCESS', data, {root: true})
        }
        return data
    },
    newLike({commit}, data){
        commit('NEW_LIKE', data)
    },
    removeLike({commit}, data){
        commit('REMOVE_LIKE', data)
    },

    ////////////////////////////////////// flags

    async createFlag({commit, rootState},data){
        let response = await ProfileService.flagCreate(data)

        if (response.data.message === 'successful') {
            data['flag'] = response.data.flag
            data['items'] = rootState.items

            if (data.where === 'profile') {
                commit('FLAG_CREATE_SUCCESS', data)
            } else if (data.where === 'home') {
                commit('home/FLAG_CREATE_SUCCESS', data, {root: true})
            } else if (data.where === 'dashboard') {
                commit('dashboard/FLAG_CREATE_SUCCESS', data, {root: true})
            }
            return {status: true,flag: response.data.flag}
        }else {
            commit('PROFILE_FAILURE','flagging unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
    },
    async deleteFlag({commit, rootState},data){
        let response = await ProfileService.flagDelete(data)

        if (response.data.message === 'successful') {
            data['items'] = rootState.items

            if (data.where === 'profile') {
                commit('FLAG_DELETE_SUCCESS', data)
            } else if (data.where === 'home') {
                commit('home/FLAG_DELETE_SUCCESS', data, {root: true})
            } else if (data.where === 'dashbaord') {
                commit('dashbaord/FLAG_DELETE_SUCCESS', data, {root: true})
            }
            return {data,status: true}
        }else {
            commit('PROFILE_FAILURE','unflagging unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
    },
    newFlag({ commit, rootState }, data) {
        data['items'] = rootState.items
        
        commit('NEW_FLAG', data)
    },

    ////////////////////////////////////// attachments

    async createAttachment({commit, rootGetters},data){
        let response = await ProfileService.attachmentCreate(data)

        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','attaching unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        data['attachment'] = response.data.attachment

        if (currentRouteName === 'profile') {
            commit('ATTACHMENT_CREATE_SUCCESS', data)
        }
        
        if (currentRouteName === 'home') {
            commit('home/ATTACHMENT_CREATE_SUCCESS', data, {root: true})
        }
        
        if (currentRouteName === 'dashboard') {
            commit('dashboard/ATTACHMENT_CREATE_SUCCESS', data, {root: true})
        }
        return {status: true,attachment: response.data.attachment}
    },
    async deleteAttachment({commit, rootGetters},data){
        let response = await ProfileService.attachmentDelete(data)

        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','unattaching unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'profile') {
            commit('ATTACHMENT_DELETE_SUCCESS', data)
        }
        
        if (currentRouteName === 'home') {
            commit('home/ATTACHMENT_DELETE_SUCCESS', data, {root: true})
        }
        
        if (currentRouteName === 'dashbaord') {
            commit('dashbaord/ATTACHMENT_DELETE_SUCCESS', data, {root: true})
        }
        return {data,status: true}
    },
    newAttachment({commit}, attachment){
        commit('NEW_ATTACHMENT', attachment)
    },
    removeAttachment({commit}, attachmentInfo){
        commit('REMOVE_ATTACHMENT', attachmentInfo)
    },

    ////////////////////////////////////// marks

    async createMark({},data){
        let response = await ProfileService.createMark(data)
        
        if (response.data.message === 'successful') {
            return {status: true, data: response.data.mark}
        }
        
        return {status: false, response}
    },
    async updateMark({},data){
        let response = await ProfileService.updateMark(data)
        
        if (response.data.message === 'successful') {
            return {status: true, mark: response.data.mark}
        }
        
        return {status: false, ...response}
    },
    async deleteMark({},data){
        let response = await ProfileService.deleteMark(data)
        
        if (response.data.message === 'successful') {
            return {status: true}
        }
        
        return {status: false, ...response}
    },
    async getAnswerMarks({},data){
        let response = await ProfileService.getAnswerMarks(data)

        if (response.data.data) {
            return {
                status: true,
                data: response.data.data,
                next: response.data.links.next
            }
        }

        return {status: false, response}
    },


    //////////////////////////////////////// comments

    async createComment({commit, rootGetters},mainData){
        commit('COMMENTING_START')
        let response = await ProfileService.createComment(mainData)
        commit('COMMENTING_END')
        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','commenting unsuccessful')
            return 'unsuccessful'
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'dashboard') {
            commit('dashboard/COMMENT_SUCCESS',response.data, {root: true})
        }
        
        if (currentRouteName === 'home') {
            commit('home/COMMENT_SUCCESS', {
                comment: response.data.comment,
                item: mainData.formData.get('item'),
                itemId: mainData.formData.get('itemId'),
            }, { root: true })
        }
        
        if (currentRouteName === 'profile') {
            commit('COMMENT_SUCCESS', {
                comment: response.data.comment,
                item: mainData.formData.get('item'),
                itemId: mainData.formData.get('itemId'),
            })
        }
        return response.data
    },
    async deleteComment({commit, rootGetters},data){
        commit('COMMENTING_START')
        let response = await ProfileService.deleteComment(data)

        commit('COMMENTING_END')
        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','comment update unsuccessful')
            return 'unsuccessful'
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'profile') {
            commit('COMMENT_DELETE_SUCCESS',data)
        }
        
        if (currentRouteName === 'home') {
            commit('home/COMMENT_DELETE_SUCCESS',data, {root: true})
        }
        
        if (currentRouteName === 'dashboard') {
            commit('dashboard/COMMENT_DELETE_SUCCESS',data, {root: true})
        }
        return data
    },
    async updateComment({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.updateComment(data)

        commit('COMMENTING_END')
        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','comment update unsuccessful')
            return 'unsuccessful'
        }

        if (data.data.where === 'profile') {
            commit('COMMENT_UPDATE_SUCCESS',{
                comment: response.data.comment,
                item: data.data.item,
                itemId: data.data.itemId
            })
        }
        
        if (data.data.where === 'home') {
            commit('home/COMMENT_UPDATE_SUCCESS',{
                comment: response.data.comment,
                item: data.data.item,
                itemId: data.data.itemId
            }, {root: true})
        }
        
        if (data.data.where === 'dashboard') {
            commit('dashboard/COMMENT_UPDATE_SUCCESS',response.data, {root: true})
        }
        return response.data
    },
    async getComment({commit}, data){
        let response = await ProfileService.commentGet(data)
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('COMMENT_GET_SUCCESS',response.data)
            } else if (data.where === 'home') {
                commit('COMMENT_GET_SUCCESS',response.data)
            } else if (data.where === 'dashboard') {
                commit('dashboard/COMMENT_GET_SUCCESS',response.data,{root: true})
            }
            return response.data.comment
        } else {
            return 'unsuccessful'
        }
    },
    async getComments({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.commentsGet(data)

        commit('COMMENTING_END')
        if (response.data.data) {
                
            let commentsData = {
                    status:response.data.links.next,
                    data: response.data,
                    currentPage: response.data.meta.current_page
                }

            if (data.where === 'dashboard') {
                commit('dashboard/GET_COMMENTS_SUCCESS',response.data.data, {root: true})
            }

            return commentsData
        }else {
            commit('PROFILE_FAILURE','commenting was unsuccessful')
            return 'unsuccessful'
        }
    },

    clearComments({commit}){
        commit('COMMENTS_CLEAR')
    },

    newComment({commit}, comment){
        commit('NEW_COMMENT', comment)
    },
    replaceComment({commit}, comment){
        commit('REPLACE_COMMENT', comment)
    },
    removeComment({commit}, commentInfo){
        commit('REMOVE_COMMENT', commentInfo)
    },

    ///////////////////////////////////////// grades
    
    async createGrade({commit},mainData){

        let response = await ProfileService.gradeCreate(mainData)

        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of grade unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async createGradeAlias({commit},mainData){

        let response = await ProfileService.gradeAliasCreate(mainData)
        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of grade alias unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async getGrades({commit},data){

        let response = await ProfileService.gradesGet(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response.data.message}
        }
    },
    async searchGrades({commit},data){

        let response = await ProfileService.gradesSearch(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response.data.message}
        }
    },

    ///////////////////////////////////////// programs
    
    async createProgram({commit},mainData){

        let response = await ProfileService.programCreate(mainData)

        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of program unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async createProgramAlias({commit},mainData){

        let response = await ProfileService.programAliasCreate(mainData)
        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of program alias unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async getPrograms({commit},data){

        let response = await ProfileService.programsGet(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, response}
        }
    },
    async searchPrograms({commit},data){

        let response = await ProfileService.programsSearch(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false,response}
        }
    },

    ///////////////////////////////////////// courses
    
    async createCourse({commit},mainData){

        let response = await ProfileService.courseCreate(mainData)

        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of course unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async createCourseAlias({commit},mainData){

        let response = await ProfileService.courseAliasCreate(mainData)
        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of course alias unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async getCourses({commit},data){

        let response = await ProfileService.coursesGet(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response.data.message}
        }
    },
    async searchCourses({commit},data){

        let response = await ProfileService.coursesSearch(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, message: response.data.message}
        }
    },

    ///////////////////////////////////////// subject
    
    async createSubject({commit},mainData){

        let response = await ProfileService.subjectCreate(mainData)

        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of subject unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async createSubjectAlias({commit},mainData){

        let response = await ProfileService.subjectAliasCreate(mainData)
        if (response.data.message === 'successful') {

            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','creation of subject alias unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async getSubjects({commit},data){

        let response = await ProfileService.subjectsGet(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, response}
        }
    },
    async searchSubjects({commit},data){

        let response = await ProfileService.subjectsSearch(data)
        
        if (response.data.data) {

            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }else {
            return {status: false, response}
        }
    },

    ///////////////////////////////////////// answers
    
    async createAnswer({commit},mainData){
        commit('COMMENTING_START')
        let response = await ProfileService.createAnswer(mainData)
        // console.log('comment data', data)
        commit('COMMENTING_END')
        if (response.data.message === 'successful') {
            commit('ANSWER_CREATE_SUCCESS',{
                data: mainData.data, response: response.data})
            return response.data
        }else {
            commit('PROFILE_FAILURE','creation of answer unsuccessful')
            return response.data.message
        }
    },
    async deleteAnswer({commit},data){
        let response = await ProfileService.deleteAnswer(data)
        
        if (response.data.message !== 'successful') {
            return {status: false, message: response.data.message}
        }

        return {status: true}
    },
    async updateAnswer({commit},data){
        let response = await ProfileService.updateAnswer(data)

        if (response.data.message !== 'successful') {
            return {status: false, message: response.data.message}
        }
        
        return {status: true, answer: response.data.answer}
    },
    async getAnswers({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.answersGet(data)

        commit('COMMENTING_END')
        if (response.data.data) {
            return {
                    status:response.data.links.next,
                    data: response.data
                }
        }else {
            commit('PROFILE_FAILURE','commenting was unsuccessful')
            return 'unsuccessful'
        }
    },

    ////////////////////////////////// discussions

    async createDiscussion({commit},data){
        let response = await ProfileService.discussionCreate(data.formData)

        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('DISCUSSION_CREATE_SUCCESS',response.data)
            } else if (data.where === 'home') {
                commit('home/DISCUSSION_CREATE_SUCCESS',response.data, {root: true})
            }
            return {status: true}
        }else {
            commit('PROFILE_FAILURE','discussion unsuccessful')
            return {status: false, response: response}
        }
    },
    async discusionContributionResponse({commit},data){
        let response = await ProfileService.discusionContributionResponse(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
                discussionMessage: response.data.discussionMessage
            }
        } else {
            return {status: false, response}
        }
    },
    async joinItemResponse({commit},data){
        let response = await ProfileService.joinItemResponse(data)

        if (response.data.message === 'successful') {
            return {
                status: true
            }
        } else {
            return {status: false, response}
        }
    },
    async invitationItemResponse({commit, rootGetters},data){
        let response = await ProfileService.invitationItemResponse(data)

        if (response.data.message !== 'successful') {
            return {status: false, response}
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'home') {
            
            if (data.type !== 'marker') {

                commit('home/REMOVE_ITEM_PENDING_PARTICIPANT', {
                    ...data,
                    userId: rrotGetter.getUser.id
                }, { root: true })
            }

            if (data.action === 'accepted') {

                commit('home/NEW_ITEM_PARTICIPANT', {
                    participant: response.data.participant,
                    marker: response.data.marker,
                    ...data
                }, { root: true })
            }
        }

        if (currentRouteName === 'profile') {
            
            if (data.type !== 'marker') {

                commit('REMOVE_ITEM_PENDING_PARTICIPANT', {
                    ...data,
                    userId: rrotGetter.getUser.id
                }, { root: true })
            }

            if (data.action === 'accepted') {
                
                commit('NEW_ITEM_PARTICIPANT', {
                    participant: response.data.participant,
                    marker: response.data.marker,
                    ...data
                }, { root: true })
            }
        }
        return {status: true}
    },
    async updateDiscussion({commit},data){
        let response = await ProfileService.discussionUpdate(data)

        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('DISCUSSION_UPDATE_SUCCESS',response.data)
            } else if (data.where === 'home') {
                commit('home/DISCUSSION_UPDATE_SUCCESS',response.data, {root: true})
            }
            return {status: true}
        }else {
            commit('PROFILE_FAILURE','discussion update unsuccessful')
            return {status: false, response: response}
        }
    },
    async deleteDiscussion({commit, rootGetters},data){
        let response = await ProfileService.discussionDelete(data)

        if (response.data.message !== 'successful') {
            commit('PROFILE_FAILURE','discussion deletion unsuccessful')
            return {status: false, response: response}
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'profile') {
            commit('DISCUSSION_DELETE_SUCCESS',data)
        }
        
        if (currentRouteName === 'home') {
            commit('home/DISCUSSION_DELETE_SUCCESS',data, {root: true})
        }
        return {status: true}
    },
    async deleteDiscussionMessage({commit},data){
        let response = await ProfileService.deleteDiscussionMessage(data)

        if (response.data.message === 'successful') {
            return {
                status: true, 
                discussionMessage: response.data.discussionMessage,
            }
        } else {
            return {status: false, response: response}
        }
    },
    async updateParticpantState({commit},data){
        let response = await ProfileService.updateParticpantState(data)

        if (response.data.message === 'successful') {
            return {
                status: true, 
                participant: response.data.participant,
            }
        } else {
            return {status: false, response: response}
        }
    },
    async deleteDiscussionParticipant({commit},data){
        let response = await ProfileService.deleteDiscussionParticipant(data)

        if (response.data.message === 'successful') {
            return {
                status: true,
            }
        } else {
            return {status: false, response: response}
        }
    },
    async getDiscussionParticipants({commit},data){
        let response = await ProfileService.getDiscussionParticipants(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response: response}
        }
    },
    async getDiscussionMessages({commit},data){
        let response = await ProfileService.getDiscussionMessages(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response: response}
        }
    },
    async sendDiscussionMessage({commit},data){
        let response = await ProfileService.sendDiscussionMessage(data)

        if (response.data.message === 'successful') {
            return {
                status: true, 
                discussionMessage: response.data.discussionMessage
            }
        } else {
            return {status: false, response: response}
        }

    },
    async inviteAccount({commit, rootGetters},data){
        let response = await ProfileService.inviteAccount(data)

        if (response.data.message !== 'successful') {
            return {status: false, response: response}
        }

        if (!response.data.pendingParticipant) {
            return {status: true}
        }
        
        const currentRouteName = rootGetters.getCurrentRouterName

        if (currentRouteName === 'profile') {
            commit('CREATE_PENDING_ITEM_PARTICIPANT',{
                pendingParticipant: response.data.pendingParticipant,
                ...data.computedItem
            })
        }
        
        if (currentRouteName === 'home') {
            commit('home/CREATE_PENDING_ITEM_PARTICIPANT',{
                pendingParticipant: response.data.pendingParticipant,
                ...data.computedItem
            }, {root: true})
        }

        return {status: true}
    },
    async itemSearch({commit},data){
        let response = await ProfileService.itemSearch(data)

        if (response.data.data) {
            return {
                status: true, 
                data: response.data.data,
                next: response.data.links.next
            }
        }
            
        return {status: false, response: response}
    },
    async joinItem({commit, rootGetters}, data){
        let response = await ProfileService.joinItem(data)

        if (response.data.message !== 'successful') {
            return {status: false, response: response}
        }
        const currentRouteName = rootGetters.getCurrentRouterName

        if (response.data.pendingParticipant  && currentRouteName === 'home') {
            commit('home/CREATE_PENDING_ITEM_PARTICIPANT',{
                pendingParticipant: response.data.pendingParticipant,
                ...data.computedItem
            }, {root: true})
        } 

        if (response.data.pendingParticipant  && currentRouteName === 'profile') {
            commit('CREATE_PENDING_ITEM_PARTICIPANT',{
                pendingParticipant: response.data.pendingParticipant,
                ...data.computedItem
            })
        } 
        
        if ((response.data.pendingParticipant || response.data.marker)  && currentRouteName === 'home') {
            commit('home/CREATE_ITEM_PARTICIPANT',{
                participant: response.data.participant,
                marker: response.data.marker,
                ...data.computedItem
            }, {root: true})
        }
        
        if ((response.data.pendingParticipant || response.data.marker)  && currentRouteName === 'profile') {
            commit('CREATE_ITEM_PARTICIPANT',{
                participant: response.data.participant,
                marker: response.data.marker,
                ...data.computedItem
            })
        }

        return {status: true}
    },
    newDiscussion({commit}, discussion){
        commit('NEW_DISCUSSION', discussion)
    },
    replaceDiscussion({commit}, discussion){
        commit('REPLACE_DISCUSSION', discussion)
    },
    removeDiscussion({commit}, discussionInfo){
        commit('REMOVE_DISCUSSION', discussionInfo)
    },
    newItemParticipant({commit}, data){
        commit('NEW_ITEM_PARTICIPANT', data)
    },
    removeItemParticipant({commit}, data){
        commit('REMOVE_ITEM_PARTICIPANT', data)
    },
    updateItemParticipant({commit}, data){
        commit('UPDATE_ITEM_PARTICIPANT', data)
    },
    newItemPendingParticipant({commit}, data){
        commit('NEW_ITEM_PENDING_PARTICIPANT', data)
    },
    removeItemPendingParticipant({commit}, data){
        commit('REMOVE_ITEM_PENDING_PARTICIPANT', data)
    },

    ////////////////////////////////// posts

    clearPosts({commit}){
        commit('CLEAR_POSTS')
    },
    async createPost({commit},data){
        commit('POST_START')
        let response = await ProfileService.postCreate(data.formData)

        commit('POST_END')
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('POST_CREATE_SUCCESS',response.data)
            } else if (data.where === 'home') {
                commit('home/POST_CREATE_SUCCESS',response.data, {root: true})
            }
            return 'success'
        }else {
            commit('PROFILE_FAILURE','post unsuccessful')
            return 'unsuccessful'
        }
    },
    async getPost({commit},data){
        let response = await ProfileService.postGet(data)

        if (response.data.message === 'successful') {
            return response.data.post
        }else {
            return 'unsuccessful'
        }
    },
    async updatePost({commit},data){
        commit('POST_START')
        let response = await ProfileService.postUpdate(data)

        commit('POST_END')
        if (response.data.message === 'successful') {
            if (data.otherData.where === 'profile') {
                commit('POST_UPDATE_SUCCESS',response.data)
            } else if (data.otherData.where === 'home') {
                commit('home/POST_UPDATE_SUCCESS',response.data, {root: true})
            }
            return 'successful'
        }else {
            commit('PROFILE_FAILURE','post update unsuccessful')
            return 'unsuccessful'
        }
    },
    newPost({commit}, post){
        commit('NEW_POST', post)
    },
    replacePost({commit}, post){
        commit('REPLACE_POST', post)
    },
    removePost({commit}, postInfo){
        commit('REMOVE_POST', postInfo)
    },
    async deletePost({commit},data){
        commit('POST_START')
        let response = await ProfileService.postDelete(data)

        commit('POST_END')
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('POST_DELETE_SUCCESS',data)
            } else if (data.where === 'home') {
                commit('home/POST_DELETE_SUCCESS',data, {root: true})
            }
            return 'successful'
        }else {
            commit('PROFILE_FAILURE','post deletion unsuccessful')
            return 'unsuccessful'
        }
    },
    async getProfilePosts({commit},data){
        commit('LOADING_START')
        console.log('responsedata',data)
        let response = await ProfileService.profilePostsGet(data)
        commit('LOADING_END')
        if (response.data.data) {
            commit('POSTS_SUCCESS',response.data)
            if (response.data.hasOwnProperty('links')) {
                
                return response.data.links.next
            } else {
                return null
            }
        }else {
            commit('PROFILE_FAILURE','retrieving posts unsuccessful')
        }
    },
    async getProfileAccounts({}, data) {
        let response = await ProfileService.getProfileAccounts(data)

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
    ////////////////////
}

export default actions