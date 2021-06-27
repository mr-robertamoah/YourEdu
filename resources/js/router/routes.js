const Login = () => import(/* webpackChunkName: 'Login' */ '../views/Login.vue')
const Registration = () => import(/* webpackChunkName: 'Registration' */ '../views/Registration.vue')
const Home = () => import(/* webpackChunkName: 'Home' */ '../views/Home.vue')
const Dashboard = () => import(/* webpackChunkName: 'Dashboard' */ '../views/Dashboard.vue')
const Welcome = () => import(/* webpackChunkName: 'Welcome' */ "../views/Welcome.vue")
const About = () => import(/* webpackChunkName: 'About' */ "../views/About.vue")
const Profile = () => import(/* webpackChunkName: 'Profile' */ "../views/Profile.vue")
const PostModal = () => import(/* webpackChunkName: 'PostModal' */ "../components/PostModal.vue")
const WorkAnsweringForm = () => import(/* webpackChunkName: 'WorkAnsweringForm' */ "../components/forms/WorkAnsweringForm.vue")
const DiscussionModal = () => import(/* webpackChunkName: 'DiscussionModal' */ "../components/DiscussionModal.vue")
const ModalSwitcher = () => import(/* webpackChunkName: 'ModalSwitcher' */ "../components/ModalSwitcher.vue")
const ViewComments = () => import(/* webpackChunkName: 'ViewComments' */ "../components/ViewComments.vue")
const CatchAll = () => import(/* webpackChunkName: 'CatchAll' */ "../views/CatchAll.vue")
const Testing = () => import(/* webpackChunkName: 'Testing' */ "../views/Testing.vue")



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
        path: '*',
        redirect: '404'
    },
]

export default routes