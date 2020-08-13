import { TokenService } from "../services/token.service"

const state = {
    loading : false,
    loggedin : false,
    authenticating : false,
    authenticatingUser : false,
    authenticatingErrorCode : 0,
    authenticatingErrorMessage : null,
    accessToken : TokenService.getToken(),
    refreshTokenPromise : null,
    user:null,
    followedby:null,
    validationErrors: null,
    userFollowRequest: null,
    requestingStatus: null,
    requestMessage: '',
}

export default state