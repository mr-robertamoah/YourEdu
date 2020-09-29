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
    async gradesGet(){
        try {

            let response = await ApiService.get(`/api/grades`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async gradesSearch(data){
        try {

            let response = await ApiService.get(`/api/grades/${data}`)
    
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
    async programsGet(){
        try {

            let response = await ApiService.get(`/api/programs`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async programsSearch(data){
        try {

            let response = await ApiService.get(`/api/programs/${data}`)
    
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
    async coursesGet(){
        try {

            let response = await ApiService.get(`/api/courses`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async coursesSearch(data){
        try {

            let response = await ApiService.get(`/api/courses/${data}`)
    
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
    async subjectsGet(data){
        try {

            let response = await ApiService.get(`/api/subjects`)
    
            return response
        } catch (error) {
            
            return error.response
        }
    },
    async subjectsSearch(data){
        try {

            let response = await ApiService.get(`/api/subjects/${data}`)
    
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
        try {
            let response = await ApiService.delete(`/api/attachment/${data.attachmentId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// likes

    async likeCreate(main){
        try {
            let {accountId, account, item, itemId} = main
            let response = await ApiService.post(`/api/${item}/${itemId}/like`,{
                account, accountId
            })
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async likeDelete(data){
        try {
            let response = await ApiService.delete(`/api/like/${data.likeId}`)
    
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

    async saveCreate(main){
        try {
            let {accountId, account, item, itemId} = main
            let response = await ApiService.post(`/api/${item}/${itemId}/save`,{
                account, accountId
            })
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async saveDelete(data){
        try {
            let response = await ApiService.delete(`/api/save/${data.saveId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// flags

    async flagCreate(main){
        try {
            let {accountId, account, reason, item, itemId} = main
            let response = await ApiService.post(`/api/${item}/${itemId}/flag`,{
                account, accountId, reason
            })
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async flagDelete(data){
        try {
            let response = await ApiService.delete(`/api/flag/${data.flagId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },

    //////////////////////////////////// marks

    async markCreate(main){
        try {
            let {item, itemId} = main
            let response = await ApiService.post(`/api/${item}/${itemId}/mark`,main)
    
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
    async answerDelete(data){
        let {answerId} = data
        try {
            let response = await ApiService.delete(`/api/answer/${answerId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async answerCreate(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.post(`/api/${data.item}/${data.itemId}/answer`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async answerUpdate(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.post(`/api/answer/${data.itemId}`,
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
    async commentDelete(data){
        let {commentId} = data
        try {
            let response = await ApiService.delete(`/api/comment/${commentId}`)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async commentCreate(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.post(`/api/${data.item}/${data.itemId}/comment`,
                formData,true)
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async commentUpdate(main){
        
        try {
            let {data, formData} = main
            let response = await ApiService.post(`/api/comment/${data.itemId}`,
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
                .post(`/api/post/${otherData.postId}/${otherData.account}/${otherData.accountId}`,
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
            let response = await ApiService.delete(`/api/post/${postId}/${account}/${accountId}`)
    
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
            // console.log('profile post data',data )
            let response = null
            if (nextPage) {
                // console.log('nextPageurl',`/api/posts/${account}/${accountId}?page=${nextPage}`)
                response = await ApiService.get(`/api/posts/${account}/${accountId}?page=${nextPage}`)
            } else{
                // console.log('nextPageurl',`/api/posts/${account}/${accountId}`)
                response = await ApiService.get(`/api/posts/${account}/${accountId}`)
            }
    
            return response
        } catch (error) {
            return error.response
        }
    },
    //////////////////////////////////////////////////////////
}

export {ProfileService}