import Vue from 'vue'
import Router from 'vue-router'
import routes from './routes'
import { routerBeforeEach } from './router';


Vue.use(Router)

const router = new Router({
    mode:'history',
    history:true,
    routes
})



router.beforeEach((to,from,next)=>{
    routerBeforeEach(to,from,next)
})

export default router


