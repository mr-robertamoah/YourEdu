import ApiService from "./api.service";

const ProfileService = {
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

    ////////////////////////////////////////////

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

    /////////////////////////////////////
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
    async postsGet(data,nextPage){
        let {account, accountId} = data
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
    async profilePostsGet(data){
        let {account, accountId , nextPage} = data
        try {
            // console.log('profile post data',data )
            let response = null
            if (nextPage) {
                console.log('nextPageurl',`/api/posts/${account}/${accountId}?page=${nextPage}`)
                response = await ApiService.get(`/api/posts/${account}/${accountId}?page=${nextPage}`)
            } else{
                console.log('nextPageurl',`/api/posts/${account}/${accountId}`)
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