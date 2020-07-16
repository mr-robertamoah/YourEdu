const TOKEN_KEY = 'YourEdu_access_token'
const REFRESH_TOKEN_KEY = 'YourEdu_refresh_token'
const USER_KEY = 'YourEdu_user'


const TokenService = {
    setToken(accessToken){
        localStorage.setItem(TOKEN_KEY,accessToken)
    },

    setUser(user){
        localStorage.setItem(USER_KEY,JSON.stringify(user))
    },
    
    getUser(){
        return JSON.parse(localStorage.getItem(USER_KEY))
    },
    
    getToken(){
        return localStorage.getItem(TOKEN_KEY)
    },
    
    removeUser(){
        localStorage.removeItem(USER_KEY)
    },
    
    removeToken(){
        localStorage.removeItem(TOKEN_KEY)
    },

    setRefreshToken(refreshToken){
        localStorage.setItem(REFRESH_TOKEN_KEY,refreshToken)
    },
    
    getRefreshToken(){
        return localStorage.getItem(REFRESH_TOKEN_KEY)
    },
    
    removeRefreshToken(){
        localStorage.removeItem(REFRESH_TOKEN_KEY)
    }
}

export {TokenService}