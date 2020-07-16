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
        const {username,email,password, 
            passwordConfirmation, firstName, lastName,
            otherNames, dob} = sentData
        
        const response = await ApiService.post(url, {username, email, password, 
            passwordConfirmation, firstName, lastName, 
            otherNames, dob})
        // console.log(response)
        let token = response.data.token
        // let user = response.data.user

        // if (user) {
        //     TokenService.setUser(user)
        // }
        if (token) {
            TokenService.setToken(token)
            ApiService.setHeaderAuth()
        }

        // ApiService.mount401Interceptor()

        return response.data
    },

    async getUser(url){

        const response = await ApiService.get(url)

        // console.log('user',response)
        return response.data
    }
}

const UserService = {

    async editUser(mainData){
        let {user_id, data} = mainData
        
        try {
            let response = await ApiService.post(`/api/user/${user_id}/edit`,data)

            console.log('response',response)

            return response
        } catch (error) {
            return error.response
        }
    },

    async getSecretQuestions(){
        try {
            let response = await ApiService.get('/api/secret')

            return response
        } catch (error) {
            return error.response
        }
    },

    login : async function(data){
        try {
            return await loginRegister.loginRegisterTry('/api/login', data)
        } catch (error) {
            // throw new AuthenticationError(error.response.status, error.response.data.details)
            throw new AuthenticationError(error.response.status, 
                error.response.statusText, error.response.data.errors)
        }
    },

    register : async function(data){
        try {
            return await loginRegister.loginRegisterTry('/api/register', data)
        } catch (error) {
            // console.log('thow new', error.response)
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

    logout(){
        TokenService.removeToken()
        // TokenService.removeUser()
        ApiService.removeHeaderAuth()

        ApiService.unmount401Interceptor()

        return true
    }
}

export default UserService

export { UserService,  AuthenticationError}