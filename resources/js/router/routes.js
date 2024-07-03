import Login from '../views/Login.vue'
import Registration from '../views/Registration.vue'
import Home from '../views/Home.vue'
import Dashboard from '../views/Dashboard.vue'
import Welcome from "../views/Welcome.vue"
import About from "../views/About.vue"
import Profile from "../views/Profile.vue"
import PostModal from "../components/PostModal.vue"
import WorkAnsweringForm from "../components/forms/WorkAnsweringForm.vue"
import DiscussionModal from "../components/DiscussionModal.vue"
import ModalSwitcher from "../components/ModalSwitcher.vue"
import ViewComments from "../components/ViewComments.vue"
import CatchAll from "../views/CatchAll.vue"
import Testing from "../views/Testing.vue"

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
            doesntRequireLoginNot:true
        }
    },
    {
        name: 'register',
        path: '/register',
        component: Registration,
        meta:{
            doesntRequireLoginNot:true
        }
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard,
        meta:{
            requiresLogin:true
        },
        children: [
            {
                path: 'work/:assessmentId',
                component: WorkAnsweringForm,
                name: 'work'
            }
        ]
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
        name: 'comment',
        path: '/comment/:itemId',
        component: ViewComments,
    },
    {
        name: 'post',
        path: '/post/:itemId',
        component: PostModal,
    },
    {
        name: 'discussion',
        path: '/discussion/:itemId',
        component: DiscussionModal,
    },
    {
        name: 'answer',
        path: '/answer/:itemId',
        component: ViewComments,
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
        name: 'item',
        path: '/:item/:itemId',
        component: ModalSwitcher,
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
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: CatchAll
    },
]

export default routes