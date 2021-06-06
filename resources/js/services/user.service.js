import { TokenService } from "./token.service";
import ApiService from "./api.service";


class AuthenticationError extends Error{
    constructor(errorCode, errorMessage,errors){
        super(errorMessage)
        this.name = this.constructor.name
        this.errorCode = errorCode
        this.errorMessage = errorMessage
        this.errors = errors
    }
}

const loginRegister = {
    async loginRegisterTry(url, sentData){
        let response = null
        if (url.includes('login')) {
            const {username,email,password} = sentData
            
            response = await ApiService.post(url, {username, email, password})
        } else if (url.includes('register')) {
            // console.log(sentData)
            const {username,email,password, 
                password_confirmation, first_name, last_name,
                other_names, dob} = sentData
            
            response = await ApiService.post(url, {username, email, password, 
                password_confirmation, first_name, last_name, 
                other_names, dob})
        }

        let token = response.data.token

        if (token) {
            TokenService.setToken(token)
            ApiService.setHeaderAuth()
        }

        // console.log('response from main', response)
        return response.data
    },

    async getUser(url){

        const response = await ApiService.get(url)

        return response.data
    }
}

const UserService = {

    ////////////////////////////////////////////////////////////////////account

    async accountCreate(data){
        
        try {
            let response = await ApiService.post(`/api/user/account`,data)

            return response
        } catch (error) {
            return error.response
        }
    },

////////////////////////////////////////////////////// requests
    async declineFollowRequest(data){
        try {
            let response = await ApiService.post(`/api/follow/request/decline`, data)
            
            return response
        } catch (error) {
            return error.response
        }
    },
    async acceptFollowRequest(data){
        try {
            let response = await ApiService.post(`/api/follow/request/accept`, data)
            
            return response
        } catch (error) {
            return error.response
        }
    },
    async followingsGet(){
        try {
            let response = await ApiService.get(`/api/follow/user/followings`)
            
            return response
        } catch (error) {
            return error.response
        }
    },
    async followersGet(){
        try {
            let response = await ApiService.get(`/api/follow/user/followers`)
            
            return response
        } catch (error) {
            return error.response
        }
    },
    async getUserRequests(data){
        let {nextPage} = data

        try {
            let response = await ApiService.get(`/api/user/requests?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async schoolRequestResponse(data){
        try {
            let response = await ApiService.post(`/api/user/request/response`,data)

            return response
        } catch (error) {
            return error.response
        }
    },
    async userNotifications(data){

        try {
            let response = await ApiService.get(`/api/user/notifications?page=${data.nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async markNotifications(){

        try {
            let response = await ApiService.post(`/api/user/notifications/mark`)

            return response
        } catch (error) {
            return error.response
        }
    },
    async markOtherNotifications(data){

        try {
            let response = await ApiService.post(`/api/user/notifications/mark`,data)

            return response
        } catch (error) {
            return error.response
        }
    },
    
//////////////////////////////////////////////////////////////////// users

    async userFlaggedGet(main){
        let {type,nextPage} = main
        try {
            let response = await ApiService.get(`/api/user/flagged?page=${nextPage}&type=${type}`)

            return response
        } catch (error) {
            return error.response
        }
    },

    async userSavedGet(main){
        let {type,nextPage} = main
        try {
            let response = await ApiService.get(`/api/user/saved?page=${nextPage}&type=${type}`)

            return response
        } catch (error) {
            return error.response
        }
    },

    async findUser(main){
        let {search,nextPage} = main
        try {
            let response = await ApiService.get(`/api/user/search?page=${nextPage}`,{search})

            return response
        } catch (error) {
            return error.response
        }
    },

    async editUser(mainData){
        let {user_id, data} = mainData
        
        try {
            let response = await ApiService.put(`/api/user`,data)

            console.log('response',response)

            return response
        } catch (error) {
            return error.response
        }
    },
    
    async getSecretQuestions(){
        try {
            let response = await ApiService.get('/api/user/secrets')

            return response
        } catch (error) {
            return error.response
        }
    },
    async createSecretQuestion(data){
        try {
            let response = await ApiService.post('/api/user/secret', data)

            return response
        } catch (error) {
            return error.response
        }
    },
    login : async function(data){
        try {
            return await loginRegister.loginRegisterTry('/api/login', data)
        } catch (error) {
            throw new AuthenticationError(error.response.status, 
                error.response.statusText, error.response.data.errors)
        }
    },
    register : async function(data){
        try {
            return await loginRegister.loginRegisterTry('/api/register', data)
        } catch (error) {
            throw new AuthenticationError(error.response.status, 
                error.response.statusText, error.response.data.errors)
        }
    },
    refreshUser: async function () {
        try {
            return await loginRegister.getUser('/api/user')
        } catch (error) {
            // console.log('thown in refreshUser', error)
            throw error
        }
    },
    async userFollowRequests(data){
        try {
            let {nextPage} = data

            let response = await ApiService.get(`/api/user/followrequests?page=${nextPage}`)

            return response
        } catch (error) {
            return error.response
        }
    },

    refreshToken : async function(){
        const refresh_token = TokenService.getRefreshToken()
        
        try {
            const response = await ApiService.post('/api/refresh',  refresh_token)

            TokenService.setToken(response.data.access_token)
            TokenService.setRefreshToken(response.data.refresh_token)
            ApiService.setHeaderAuth()

            ApiService.mount401Interceptor()

            return response.data.access_token
        } catch (error) {
            throw new AuthenticationError(error.response.status, error.response.data.details)
        }
        
    },

    async logout(){
        try {

            let response = await ApiService.post('api/logout')
            
            TokenService.removeToken()
            // TokenService.removeUser()
            ApiService.removeHeaderAuth()

            ApiService.unmount401Interceptor()

            return response
        } catch (error) {
            
        }

        return true
    }
}

export default UserService

export { UserService,  AuthenticationError}