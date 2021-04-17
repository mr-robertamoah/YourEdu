import store from "../store";
import { TokenService } from "../services/token.service";

const routerBeforeEach = (to,from,next)=>{
    let isLoggedIn = !! TokenService.getToken()
    let requiresLogin = to.matched.some(record => record.meta.requiresLogin)
    let doesntRequireLoginNot = to.matched.some(record => record.meta.doesntRequireLoginNot)

    if(requiresLogin && !isLoggedIn){
        return next({
            name: 'login',
            query: {
                redirectTo: to.fullPath
            }
        })
    }
    
    if(doesntRequireLoginNot && isLoggedIn) {
        return next(from.fullPath)
    }

    return next()
    
}

export { routerBeforeEach }