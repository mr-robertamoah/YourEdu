import ApiService from "./api.service";

const HomeService = {

    /////////////////////////////////posts
    async postsGet(data){
        try {
            let response = null
            if (data.nextPage) {
                response = await ApiService.get(`/api/posts?page=${data.nextPage}`,data.params)
            } else{
                response = await ApiService.get(`/api/posts`,data.params)
            }
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async userPostsGet(data){
        try {
            let response = null
            if (data.nextPage) {
                response = await ApiService.get(`/api/user/posts?page=${data.nextPage}`,data.params)
            } else{
                response = await ApiService.get(`/api/user/posts`,data.params)
            }
    
            return response
        } catch (error) {

            return error.response
        }
    },
    async postTypesGet(data){ //same as postsGet just has additional param of postType
        try {
            let response = null
            if (data.nextPage) {
                response = await ApiService.get(`/api/posts?page=${data.nextPage}`,data.params)
            } else{
                response = await ApiService.get(`/api/posts`,data.params)
            }
    
            return response
        } catch (error) {
            return error.response
        }
    },
    async userPostTypesGet(data){
        try {
            let response = null
            if (data.nextPage) {
                response = await ApiService.get(`/api/user/posts?page=${data.nextPage}`,data.params)
            } else{
                response = await ApiService.get(`/api/user/posts`,data.params)
            }
    
            return response
        } catch (error) {

            return error.response
        }
    },

    ////////////////////////////////////////////////////search
    
    async searchGet(data){
        try {
            let response = null
            if (data.nextPage) {
                response = await ApiService.get(`/api/search?page=${data.nextPage}`,data.params)
            } else{
                response = await ApiService.get(`/api/search`,data.params)
            }
    
            return response
        } catch (error) {

            return error.response
        }
    },
}

export {HomeService}