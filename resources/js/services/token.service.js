import StorageService from "./storage.service"

const TOKEN_KEY = 'YourEdu_access_token'
const REFRESH_TOKEN_KEY = 'YourEdu_refresh_token'
const USER_KEY = 'YourEdu_user'


const TokenService = {
    setToken(accessToken){
        window.YoureduStorage.set(TOKEN_KEY,accessToken, false)
    },

    setUser(user){
        window.YoureduStorage.set(USER_KEY, user)
    },
    
    getUser(){
        return window.YoureduStorage.get(USER_KEY)
    },
    
    getToken(){
        return window.YoureduStorage.get(TOKEN_KEY, false)
    },
    
    removeUser(){
        window.YoureduStorage.remove(USER_KEY)
    },
    
    removeToken(){
        window.YoureduStorage.remove(TOKEN_KEY)
    },

    setRefreshToken(refreshToken){
        window.YoureduStorage.set(REFRESH_TOKEN_KEY,refreshToken, false)
    },
    
    getRefreshToken(){
        return window.YoureduStorage.get(REFRESH_TOKEN_KEY, false)
    },
    
    removeRefreshToken(){
        window.YoureduStorage.remove(REFRESH_TOKEN_KEY)
    }
}

export {TokenService}