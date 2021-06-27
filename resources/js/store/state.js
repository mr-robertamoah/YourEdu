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
    userFollowers: [],
    userFollowings: [],
    requestingStatus: null,
    requestMessage: '',
    items: ['post', 'assessment', 'discussion']
}

export default state