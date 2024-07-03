import { createRouter, createWebHashHistory } from 'vue-router'
import routes from './routes'
import { routerBeforeEach } from './router';

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

router.beforeEach((to,from,next)=>{
    routerBeforeEach(to,from,next)
})

export default router


