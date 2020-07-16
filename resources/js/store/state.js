import { TokenService } from "../services/token.service"

const state = {
    loading : false,
    authenticating : false,
    authenticatingUser : false,
    authenticatingErrorCode : 0,
    authenticatingErrorMessage : null,
    accessToken : TokenService.getToken(),
    refreshTokenPromise : null,
    user:null,
    validationErrors: null
}

export default state