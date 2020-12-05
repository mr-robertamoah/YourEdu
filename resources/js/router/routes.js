import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'
import Home from '../views/Home.vue'
import Dashboard from '../views/Dashboard.vue'
import Welcome from "../views/Welcome.vue";
import About from "../views/About.vue";
import Profile from "../views/Profile.vue";
import ViewComments from "../views/ViewComments.vue";
import CatchAll from "../views/CatchAll.vue";

import Testing from "../views/Testing.vue";


const routes =  [
    {
        name: 'home',
        path: '/',
        component: Home,
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta:{
            requiresLoginNot:true
        }
    },
    {
        name: 'register',
        path: '/register',
        component: Registration,
        meta:{
            requiresLoginNot:true
        }
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        meta:{
            requiresLogin:true
        }
    },
    {
        name: 'welcome',
        path: '/welcome',
        component: Welcome,
        meta:{
            requiresLogin:true
        }
    },
    {
        name: 'comments',
        path: '/comment/:commentId/comments',
        component: ViewComments,
        props: true
    },
    {
        name: 'about',
        path: '/about',
        component: About,
    },
    {
        name: 'profile',
        path: '/profile/:account/:accountId',
        component: Profile,
    },
    {
        name: 'testing',
        path: '/testing',
        component: Testing,
    },
    {
        name: '404',
        path: '/404',
        component: CatchAll,
    },
    {
        path: '*',
        redirect: '404'
    },
]

export default routes