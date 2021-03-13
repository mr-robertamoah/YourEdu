/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import router from './router'
import store from './store/index'
import { TokenService } from './services/token.service'
import ApiService from './services/api.service'
import { library } from "@fortawesome/fontawesome-svg-core"
import { faSignInAlt, faBars, faTimes, faEye, faEyeSlash, faUpload, faTrash, 
        faBan, faSearch, faUserCircle, faExclamationCircle, faEdit,
        faFileImage,faFileVideo,faFileAudio, faPlus, faMinus, faThumbsUp, faFlag,
        faChevronDown, faComment, faChevronLeft, faCheck, faCommentAlt, faCheckDouble, 
        faPen, faBookmark, faPaperclip, faHome, faEllipsisH, faEllipsisV, faLongArrowAltLeft,
        faGrin,faMicrophone,faVideo, faCamera, faPaperPlane, faImage, faMusic, faFilm,
        faArrowCircleRight, faQuestionCircle, faCameraRetro, faInfoCircle, faPencilAlt, 
        faBell, faUsers, faChevronUp, faCircle, faPause, faTrashRestore, faLongArrowAltDown, 
        faFile, faHandRock,
} from "@fortawesome/free-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { BootstrapVue } from 'bootstrap-vue'
import AppNav from './components/Nav.vue'
import VuePageTransition from 'vue-page-transition'
import { faMicrophoneAltSlash } from '@fortawesome/fontawesome-free-solid'


require('./bootstrap');

require("flatpickr");

window.Vue = require('vue');
// Install BootstrapVue
Vue.use(BootstrapVue)
Vue.use(VuePageTransition)

library.add(faUserCircle, faSignInAlt, faBars, faTimes, faEye, faEyeSlash, faUpload, 
    faTrash, faBan, faSearch, faExclamationCircle, faEdit,faFileAudio, faMicrophone, faVideo,
    faFileImage,faFileVideo, faPlus, faMinus,faThumbsUp,faFlag, faChevronDown, faCamera,
    faComment, faChevronLeft,faCheck,faCommentAlt,faCheckDouble,faPen,faBookmark,
    faPaperclip, faHome,faEllipsisH, faEllipsisV,faLongArrowAltLeft,faGrin,faPaperPlane,
    faImage,faMusic,faFilm,faArrowCircleRight, faQuestionCircle,faCameraRetro,faInfoCircle,
    faPencilAlt,faBell,faUsers,faChevronUp,faCircle,faMicrophoneAltSlash,faPause,faTrashRestore,
    faLongArrowAltDown, faFile,faHandRock
);


Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('app-nav', AppNav);



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('comment-single', require('./components/CommentSingle.vue').default);
Vue.component('view-comments', require('./components/ViewComments.vue').default);
Vue.component('small-modal', require('./components/SmallModal.vue').default);
Vue.component('main-modal', require('./components/MainModal.vue').default);
Vue.component('media-modal', require('./components/MediaModal.vue').default);
Vue.component('post-modal', require('./components/PostModal.vue').default);
Vue.component('welcome-form', require('./components/welcome/WelcomeForm.vue').default);
Vue.component('just-fade', require('./components/transitions/JustFade.vue').default);
Vue.component('user-addons', require('./components/UserAddons.vue').default);
Vue.component('addon-modal', require('./components/AddonModal.vue').default);
Vue.component('file-preview', require('./components/FilePreview.vue').default);
Vue.component('post-attachment', require('./components/PostAttachment.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
ApiService.init('http://127.0.0.1:8000/')

export const bus = new Vue();

const app = new Vue({
    el: '#app',
    router,
    store,
    created(){
        const token = TokenService.getToken()
        if (token) {
            
            ApiService.setHeaderAuth()
            store.dispatch('reloadUser')
            setTimeout(() => {
                store.dispatch('getFollowers')
                store.dispatch('getFollowings')
            }, 1000);
        } 
        else {
            
        }
        ApiService.mount401Interceptor()
    }
});
