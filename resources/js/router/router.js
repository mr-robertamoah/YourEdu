import store from "../store";
import { TokenService } from "../services/token.service";

const routerBeforeEach = (to,from,next)=>{
    let isLoggedIn = !! TokenService.getToken()
    let requiresLogin = to.matched.some(record => record.meta.requiresLogin)
    let requiresLoginNot = to.matched.some(record => record.meta.requiresLoginNot)

    if(requiresLogin){
        if(!isLoggedIn){
            return next({
                name: 'login',
                query: {
                    redirectTo: to.fullPath
                }
            })
        }else {
            return next()
        }
    }else if(requiresLoginNot) {
        if(isLoggedIn){
            // console.log('am logged in')
            return next(from.fullPath)
        }else {
            return next()
        }
    }else {
        return next()
    }
    
}

export { routerBeforeEach }