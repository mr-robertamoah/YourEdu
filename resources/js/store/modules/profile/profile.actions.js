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
        let profilesArray = [],
            computedArray = []

        if (data) {
            let {account, account_id} = data
            
            if (rootGetters.getProfiles && rootGetters.getProfiles.length) {
                profilesArray = rootGetters.getProfiles

                computedArray = profilesArray.filter(profile => {
                    return profile.params.accountId === account_id && 
                        profile.params.account === account
                })
                if (computedArray.length) {
                    commit('SET_ACTIVE_PROFILE',computedArray[0])
                    return
                }
            }
        } else {
            if (rootGetters.getProfiles && rootGetters.getProfiles.length) {
                commit('SET_ACTIVE_PROFILE',rootGetters.getProfiles[0])
                return
            }
        }
        commit('SET_ACTIVE_PROFILE', null)
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
            commit('PROFILE_FAILURE','liking unsuccessful')
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
    async deleteLike({commit},data){
        let response = await ProfileService.likeDelete(data)
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('LIKE_DELETE_SUCCESS', data)
            } else if (data.where === 'dashboard') {
                commit('dashboard/LIKE_DELETE_SUCCESS', data, {root: true})
            } else if (data.where === 'home') {
                commit('home/LIKE_DELETE_SUCCESS', data, {root: true})
            }
            return data
        }else {
            commit('PROFILE_FAILURE','liking unsuccessful')
            return 'unsuccessful'
        }
    },
    newLike({commit}, like){
        commit('NEW_LIKE', like)
    },
    removeLike({commit}, likeInfo){
        commit('REMOVE_LIKE', likeInfo)
    },

    ////////////////////////////////////// flags

    async createFlag({commit},data){
        let response = await ProfileService.flagCreate(data)

        if (response.data.message === 'successful') {
            data['flag'] = response.data.flag
            if (data.where === 'profile') {
                commit('FLAG_CREATE_SUCCESS', {data,flag: response.data.flag})
            } else if (data.where === 'home') {
                commit('home/FLAG_CREATE_SUCCESS', {data,flag: response.data.flag}, {root: true})
            } else if (data.where === 'dashboard') {
                commit('dashboard/FLAG_CREATE_SUCCESS', {data,flag: response.data.flag}, {root: true})
            }
            return {status: true,flag: response.data.flag}
        }else {
            commit('PROFILE_FAILURE','flagging unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
    },
    async deleteFlag({commit},data){
        let response = await ProfileService.flagDelete(data)

        if (response.data.message === 'successful') {
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
    newFlag({commit}, flag){
        commit('NEW_FLAG', flag)
    },

    ////////////////////////////////////// attachments

    async createAttachment({commit},data){
        let response = await ProfileService.attachmentCreate(data)

        if (response.data.message === 'successful') {
            data['attachment'] = response.data.attachment
            if (data.where === 'profile') {
                commit('ATTACHMENT_CREATE_SUCCESS', {
                    data,
                    attachment: response.data.attachment
                })
            } else if (data.where === 'home') {
                commit('home/ATTACHMENT_CREATE_SUCCESS', {
                    data,
                    attachment: response.data.attachment
                }, {root: true})
            } else if (data.where === 'dashboard') {
                commit('dashboard/ATTACHMENT_CREATE_SUCCESS', {
                    data,
                    attachment: response.data.attachment
                }, {root: true})
            }
            return {status: true,attachment: response.data.attachment}
        }else {
            commit('PROFILE_FAILURE','attaching unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
    },
    async deleteAttachment({commit},data){
        let response = await ProfileService.attachmentDelete(data)

        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('ATTACHMENT_DELETE_SUCCESS', data)
            } else if (data.where === 'home') {
                commit('home/ATTACHMENT_DELETE_SUCCESS', data, {root: true})
            } else if (data.where === 'dashbaord') {
                commit('dashbaord/ATTACHMENT_DELETE_SUCCESS', data, {root: true})
            }
            return {data,status: true}
        }else {
            commit('PROFILE_FAILURE','unattaching unsuccessful')
            return {status: false, message:'unsuccessful'}
        }
    },
    newAttachment({commit}, attachment){
        commit('NEW_ATTACHMENT', attachment)
    },
    removeAttachment({commit}, attachmentInfo){
        commit('REMOVE_ATTACHMENT', attachmentInfo)
    },

    ////////////////////////////////////// marks

    async createMark({commit},data){
        let response = await ProfileService.markCreate(data)
        if (response.data.message === 'successful') {
            return {status: true, data: response.data}
        }else {
            commit('PROFILE_FAILURE','marking unsuccessful')
            return {status: false, message: response.data.message}
        }
    },
    async getAnswerMarks({commit},data){
        let response = await ProfileService.getAnswerMarks(data)

        if (response.data.data) {
            return {
                status: true,
                data: response.data.data,
                next: response.data.links.next
            }
        } else {
            return {status: false, response}
        }
    },


    //////////////////////////////////////// comments

    async createComment({commit},mainData){
        commit('COMMENTING_START')
        let response = await ProfileService.commentCreate(mainData)
        commit('COMMENTING_END')
        if (response.data.message === 'successful') {
            if (mainData.data.where === 'dashboard') {
                commit('dashboard/COMMENT_SUCCESS',response.data, {root: true})
            } else if (mainData.data.where === 'home') {
                commit('home/COMMENT_SUCCESS',response.data, {root: true})
            } else if (mainData.data.where === 'dashboard') {
                commit('COMMENT_SUCCESS',response.data)
            }
            return response.data
        }else {
            commit('PROFILE_FAILURE','commenting unsuccessful')
            return 'unsuccessful'
        }
    },
    async deleteComment({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.commentDelete(data)

        commit('COMMENTING_END')
        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('COMMENT_DELETE_SUCCESS',data)
            } else if (data.where === 'home') {
                commit('home/COMMENT_DELETE_SUCCESS',data, {root: true})
            } else if (data.where === 'dashboard') {
                commit('dashboard/COMMENT_DELETE_SUCCESS',data, {root: true})
            }
            return data
        }else {
            commit('PROFILE_FAILURE','comment update unsuccessful')
            return 'unsuccessful'
        }
    },
    async updateComment({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.commentUpdate(data)

        commit('COMMENTING_END')
        if (response.data.message === 'successful') {
            if (data.data.where === 'profile') {
                commit('COMMENT_UPDATE_SUCCESS',response.data)
            } else if (data.data.where === 'home') {
                commit('home/COMMENT_UPDATE_SUCCESS',response.data, {root: true})
            } else if (data.data.where === 'dashboard') {
                commit('dashboard/COMMENT_UPDATE_SUCCESS',response.data, {root: true})
            }
            return response.data
        }else {
            commit('PROFILE_FAILURE','comment update unsuccessful')
            return 'unsuccessful'
        }
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
        let response = await ProfileService.answerCreate(mainData)
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
        commit('COMMENTING_START')
        let response = await ProfileService.answerDelete(data)

        commit('COMMENTING_END')
        // console.log('deleted comment', response)
        if (response.data.message === 'successful') {
            // commit('COMMENT_DELETE_SUCCESS',data)
            return response.data
        }else {
            commit('PROFILE_FAILURE','comment update unsuccessful')
            return 'unsuccessful'
        }
    },
    async updateAnswer({commit},data){
        commit('COMMENTING_START')
        let response = await ProfileService.answerUpdate(data)

        commit('COMMENTING_END')
        // console.log('update post', response)
        if (response.data.message === 'successful') {
            // commit('COMMENT_UPDATE_SUCCESS',response.data)
            return response.data
        }else {
            commit('PROFILE_FAILURE','answer update unsuccessful')
            return response.data.message
        }
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
    async joinDiscussionResponse({commit},data){
        let response = await ProfileService.joinDiscussionResponse(data)

        if (response.data.message === 'successful') {
            return {
                status: true
            }
        } else {
            return {status: false, response}
        }
    },
    async invitationDiscussionResponse({commit},data){
        let response = await ProfileService.invitationDiscussionResponse(data)

        if (response.data.message === 'successful') {
            return {
                status: true
            }
        } else {
            return {status: false, response}
        }
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
    async deleteDiscussion({commit},data){
        let response = await ProfileService.discussionDelete(data.discussionId)

        if (response.data.message === 'successful') {
            if (data.where === 'profile') {
                commit('DISCUSSION_DELETE_SUCCESS',data)
            } else if (data.where === 'home') {
                commit('home/DISCUSSION_DELETE_SUCCESS',data, {root: true})
            }
            return {status: true}
        }else {
            commit('PROFILE_FAILURE','post deletion unsuccessful')
            return {status: false, response: response}
        }
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
                discussionParticipant: response.data.discussionParticipant,
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
    async inviteParticipant({commit},data){
        let response = await ProfileService.inviteParticipant(data)

        if (response.data.message === 'successful') {
            return {
                status: true
            }
        } else {
            return {status: false, response: response}
        }

    },
    async discussionSearch({commit},data){
        let response = await ProfileService.discussionSearch(data)

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
    async joinDiscussion({commit},data){
        let response = await ProfileService.joinDiscussion(data)

        if (response.data.message === 'successful') {
            if (data.data.type === 'PRIVATE') {
                commit('CREATE_PENDING_DISCUSSION_PARTICIPANT',{
                    pendingParticipant: response.data.pendingParticipant,
                    discussionId: data.discussionId
                })
                commit('home/CREATE_PENDING_DISCUSSION_PARTICIPANT',{
                    pendingParticipant: response.data.pendingParticipant,
                    discussionId: data.discussionId
                }, {root: true})
            } else {
                commit('CREATE_DISCUSSION_PARTICIPANT',{
                    discussionParticipant: response.data.discussionParticipant,
                    discussionId: data.discussionId
                })
                commit('home/CREATE_DISCUSSION_PARTICIPANT',{
                    discussionParticipant: response.data.discussionParticipant,
                    discussionId: data.discussionId
                }, {root: true})
            }
            return {status: true}
        } else {
            return {status: false, response: response}
        }
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
    newDiscussionParticipant({commit}, discussion){
        commit('NEW_DISCUSSION_PARTICIPANT', discussion)
    },
    removeDiscussionParticipant({commit}, discussion){
        commit('REMOVE_DISCUSSION_PARTICIPANT', discussion)
    },
    updateDiscussionParticipant({commit}, discussion){
        commit('UPDATE_DISCUSSION_PARTICIPANT', discussion)
    },
    newDiscussionPendingParticipant({commit}, data){
        commit('NEW_DISCUSSION_PENDING_PARTICIPANT', data)
    },
    removeDiscussionPendingParticipant({commit}, data){
        commit('REMOVE_DISCUSSION_PENDING_PARTICIPANT', data)
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