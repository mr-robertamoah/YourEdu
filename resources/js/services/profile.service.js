import ApiService from "./api.service";

const ProfileService = {

    /////////////////////////////////////////////////////grades

    async gradeCreate(data){
        try {

            let response = await ApiService.post(`/api/grade/create`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async gradeAliasCreate(main){
        try {
            let {gradeId, data} = main
            let response = await ApiService.post(`/api/grade/${gradeId}/alias`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async gradesGet(nextPage){
        try {

            let response = await ApiService.get(`/api/grades?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async gradesSearch(data){
        let {nextPage,searchText} = data
        try {

            let response = await ApiService.get(`/api/grades/${searchText}?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },

    /////////////////////////////////////////////////////programs

    async programCreate(data){
        try {

            let response = await ApiService.post(`/api/program/create`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async programAliasCreate(main){
        try {
            let {programId, data} = main
            let response = await ApiService.post(`/api/program/${programId}/alias`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async programsGet(nextPage){
        try {

            let response = await ApiService.get(`/api/programs?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async programsSearch(data){
        let {nextPage,searchText} = data
        try {

            let response = await ApiService.get(`/api/programs/${searchText}?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },

    /////////////////////////////////////////////////////courses

    async courseCreate(data){
        try {

            let response = await ApiService.post(`/api/course/create`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async courseAliasCreate(main){
        try {
            let {courseId, data} = main
            let response = await ApiService.post(`/api/course/${courseId}/alias`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async coursesGet(nextPage){
        try {

            let response = await ApiService.get(`/api/courses?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async coursesSearch(data){
        let {nextPage,searchText} = data
        try {

            let response = await ApiService.get(`/api/courses/${searchText}?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },

    /////////////////////////////////////////////////////subjects

    async subjectCreate(data){
        try {

            let response = await ApiService.post(`/api/subject/create`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async subjectAliasCreate(main){
        try {
            let {subjectId, data} = main
            let response = await ApiService.post(`/api/subject/${subjectId}/alias`,data)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async subjectsGet(nextPage){
        try {

            let response = await ApiService.get(`/api/subjects?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async subjectsSearch(data){
        let {nextPage,searchText} = data
        try {

            let response = await ApiService.get(`/api/subjects/${searchText}?page=${nextPage}`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },

    /////////////////////////////////////////////////////follows

    async followCreate(main){
        try {
            let {accountId, account, follow, followId} = main
            let response = await ApiService.post(`/api/follow/${follow}/${followId}`,{
                account, accountId
            })
    
            return response
        } catch (error) {

            return error.response
        }
    },
    async followDelete(data){
        try {
            let response = await ApiService.delete(`/api/follow/${data.followId}`)
            
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// attachments

    async attachmentCreate(data){
        try {
            let response = await ApiService.post(`/api/attachment/create`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async attachmentDelete(data){
        let {attachmentId, adminId} = data
        try {
            let response = await ApiService.delete(`/api/attachment/${attachmentId}`,{adminId})
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// likes

    async likeCreate(data){
        try {
            
            let response = await ApiService.post(`/api/like`, data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async likeDelete(data){
        let {likeId, adminId} = data
        try {
            let response = await ApiService.delete(`/api/like/${likeId}`,{adminId})
    
            return response
        } catch (error) {
            return error.response
        }
    },

    ////////////////////////////////////chat

    async sendChatMessage(data){

        let response = null,
            {conversationId, formData} = data
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/message`,
            formData, true)

            return response
        } catch (error) {
            return error.response
        }
    },
    async sendChatAnswer(data){

        let response = null,
            {conversationId, formData} = data
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/answer`,
            formData, true)

            return response
        } catch (error) {
            return error.response
        }
    },
    async sendChatMark(main){

        let response = null,
            {conversationId, data} = main
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/markanswer`,
            data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async sendChatQuestion(data){

        let response = null,
            {conversationId, formData} = data
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/question`,
            formData, true)

            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteChatItem(data){

        let response = null
        try {
            response = await ApiService.post(`api/conversation/item/deleteitem`, data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async updateChatItemStatus(data){

        let response = null
        try {
            response = await ApiService.post(`api/conversation/item/updatestate`, data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async getChatMessages(data){

        let response = null,
            {nextPage, conversationId} = data
        try {
            response = await ApiService.get(`api/conversation/${conversationId}/messages?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async createConversation(data){

        try {
            let response = await ApiService.post(`api/conversation`,data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async getChatConversations(data){

        let response = null,
            {nextPage} = data
        try {
            response = await ApiService.get(`api/conversations?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async getBlockedConversations(data){

        let response = null,
            {nextPage} = data
        try {
            response = await ApiService.get(`api/conversations/blocked?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async getPendingConversations(data){

        let response = null,
            {nextPage} = data
        try {
            response = await ApiService.get(`api/conversations/pending?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async sendMessageResponse(main){

        let response = null,
            {data, conversationId} = main
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/response`, data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async blockConversation(main){

        let response = null,
            {conversationId, data} = main
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/block`,data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async unblockConversation(main){

        let response = null,
            {conversationId, data} = main
        try {
            response = await ApiService.post(`api/conversation/${conversationId}/unblock`,data)

            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// saves

    async saveCreate(data){
        try {
            
            let response = await ApiService.post(`/api/save`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async saveDelete(data){
        let {saveId, adminId} = data
        try {
            let response = await ApiService.delete(`/api/save/${saveId}`,{adminId})
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// flags

    async flagCreate(data){
        try {
            
            let response = await ApiService.post(`/api/flag`, data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async flagDelete(data){
        let {flagId, adminId} = data
        try {
            let response = await ApiService.delete(`/api/flag/${flagId}`,{adminId})
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// marks

    async createMark(main){
        try {
            let {item, itemId} = main
            let response = await ApiService.post(`/api/${item}/${itemId}/mark`,main)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async updateMark(main){
        try {
            let {data, markId} = main
            let response = await ApiService.put(`/api/mark/${markId}`, data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteMark(data){
        try {
            let response = await ApiService.delete(`/api/mark/${data.markId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async getAnswerMarks(main){
        try {
            let {answerId, nextPage} = main
            let response = await ApiService.get(`/api/answer/${answerId}/marks?page=${nextPage}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////////////// answers
    
    async answersGet(data){
        let {item, itemId, nextPage} = data
        try {
            let response = null
            if (!nextPage) {
                response = await ApiService.get(`/api/${item}/${itemId}/answers`)
            } else {
                response = await ApiService.get(`/api/${item}/${itemId}/answers?page=${nextPage}`)
            }
            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteAnswer(main){
        let {answerId, data} = main
        try {
            let response = await ApiService.delete(`/api/answer/${answerId}`, data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async createAnswer(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.post(`/api/${data.item}/${data.itemId}/answer`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async updateAnswer(main){
        
        try {
            let { answerId, formData } = main
            
            let response = await ApiService.put(`/api/answer/${answerId}`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    ///////////////////////////////////////// comments

    async commentGet(itemId){
        try {
            let response = await ApiService.get(`/api/comment/${itemId}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async commentsGet(data){
        let {item, itemId, nextPage} = data
        try {
            let response = null
            if (!nextPage) {
                response = await ApiService.get(`/api/${item}/${itemId}/comments`)
            } else {
                response = await ApiService.get(`/api/${item}/${itemId}/comments?page=${nextPage}`)
            }
            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteComment(data){
        let {commentId,adminId} = data
        try {
            let response = await ApiService.delete(`/api/comment/${commentId}`,{adminId})
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async createComment(main){        
        try {
            let {formData} = main
            let response = await ApiService.post(`/api/comment`,formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async updateComment(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.put(`/api/comment/${data.commentId}`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////////////// profile

    async profileGet(data){

        try {
            let {account, accountId} = data
            let response = await ApiService.get(`/api/profile/${account}/${accountId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileUpdate(mainData){

        try {
            let {profile_id, data} = mainData
            let response = await ApiService.post(`/api/profile/${profile_id}/update`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileMediaChange(mainData){ //change the state of the media 
        try {
            let {account, accountId, media, mediaId} = mainData
            let response = await ApiService
                .post(`/api/${media}/${mediaId}/change`,{account,accountId})
                
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileMediaDelete(mainData){
        try {
            let {account, accountId, media, mediaId} = mainData
            let response = await ApiService
                .post(`/api/${media}/${mediaId}/delete`,{account,accountId})
                
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileMediaGet(mainData){

        try {
            let {account, accountId, nextPage, media} = mainData
            let response = null
            if (!nextPage) {
                response = await ApiService
                .get(`/api/${account}/${accountId}/${media}`)
            } else {
                response = await ApiService
                .get(`/api/${account}/${accountId}/${media}?page=${nextPage}`)
            }

            return response
        } catch (error) {
            return error.response
        }
    },
    async profilePrivateMediaGet(mainData){

        try {
            let {account, accountId, nextPage, media} = mainData
            let response = null
            if (!nextPage) {
                response = await ApiService
                .get(`/api/${account}/${accountId}/${media}/private`)
            } else {
                response = await ApiService
                .get(`/api/${account}/${accountId}/${media}/private?page=${nextPage}`)
            }

            return response
        } catch (error) {
            return error.response
        }
    },
    async profileFileUpload(mainData){

        try {
            let {profileId, formData} = mainData
            let response = await ApiService.post(`/api/profile/${profileId}/uploadfile`,
                formData, true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileAddInfo(mainData){

        try {
            let {profile_id, data} = mainData
            let response = await ApiService.post(`/api/profile/${profile_id}/addinfo`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileMarkInfo(data){

        try {
            let response = await ApiService.post(`/api/markinfo`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profileDeleteInfo(data){

        try {
            let response = await ApiService.post(`/api/deleteinfo`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profilePicUpdate(data){
        let {profileId, formData} = data

        try {
            let response = await ApiService.post(`/api/profile/${profileId}/profilepic`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    ///////////////////////////////////// discussions
    async discussionCreate(data){
        try {
            let response = await ApiService.post(`/api/discussion`,data, true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async discussionUpdate(main){
        let {discussionId, formData} = main
        try {
            let response = await ApiService
                .put(`/api/discussion/${discussionId}`,formData)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async discussionDelete(data){
        try {
            let response = await ApiService.delete(`/api/discussion/${data.discussionId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async discusionContributionResponse(data){
        try {
            let response = await ApiService.post(`/api/discussion/contribution/response`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async joinItemResponse(data){
        try {
            let response = await ApiService.post(`/api/${data.item}/join/response`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async invitationItemResponse(data){
        try {
            let response = await ApiService.post(`/api/${data.item}/invitation/response`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteDiscussionMessage(data){
        try {
            let response = await ApiService.deete(`/api/discussion/message/${data.messageId}`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async updateParticpantState(data){
        try {
            let response = await ApiService.put(`/api/discussion/participant/${data.participantId}`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async deleteDiscussionParticipant(data){
        try {
            let response = await ApiService.delete(`/api/discussion/participant/${$data.participantId}`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async getDiscussionParticipants(data){
        let response,
            {discussionId, nextPage} = data
        try {
            response = await ApiService.get(`/api/discussion/${discussionId}/participants?page=${nextPage}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async getDiscussionMessages(data){
        let response,
            {discussionId, nextPage, type} = data
        try {
            response = await ApiService.get(`/api/discussion/${discussionId}/messages?page=${nextPage}`,{type})
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async sendDiscussionMessage(data){
        let response,
            {discussionId, formData} = data
        try {
            response = await ApiService.post(`/api/discussion/${discussionId}/message`,formData)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async inviteAccount(main){
        let response,
            {data, computedItem: item} = main
        try {
            response = await ApiService.post(`/api/${item.item}/${item.itemId}/invite`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async itemSearch(data){
        let response,
            {nextPage, params, item} = data
        try {
            response = await ApiService.get(`/api/${item}/search?page=${nextPage}`,params)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async joinItem(main){
        let response,
            {computedItem: item, data} = main
        try {
            response = await ApiService.post(`/api/${item.item}/${item.itemId}/join`,data)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    ///////////////////////////////////// posts
    async postCreate(data){
        try {
            let response = await ApiService.post(`/api/post`,data, true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async postUpdate(data){
        let {otherData, formData} = data
        try {
            let response = await ApiService
                .post(`/api/post/${otherData.postId}`,
                    formData
                )
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async postDelete(data){
        let {account,accountId, postId} = data
        try {
            let response = await ApiService
                .delete(`/api/post/${postId}`,{account, accountId})
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async postGet(postId){
        try {
            let response = await ApiService.get(`/api/post/${postId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async profilePostsGet(data){
        let {account, accountId , nextPage} = data
        try {
            let response = null
            if (nextPage) {
                response = await ApiService.get(`/api/posts/${account}/${accountId}?page=${nextPage}`)
            } else{
                response = await ApiService.get(`/api/posts/${account}/${accountId}`)
            }
    
            return response
        } catch (error) {
            return error.response
        }
    },
    //////////////////////////////////////////////////////////
    async getProfileAccounts(main){ //get accounts through profiles
        let {nextPage, data} = main
        try {
            let response = 
                await ApiService.get(`api/dashboard/search?page=${nextPage}`,data)

            return response
        } catch (error) {
            console.log(error);
            return error.response
        }
    },
}

export {ProfileService}