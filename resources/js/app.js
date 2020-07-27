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
        faChevronDown, faComment, faChevronLeft} from "@fortawesome/free-solid-svg-icons"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { BootstrapVue } from 'bootstrap-vue'
import AppNav from './components/Nav.vue'
import VuePageTransition from 'vue-page-transition'


require('./bootstrap');

require("flatpickr");

window.Vue = require('vue');
// Install BootstrapVue
Vue.use(BootstrapVue)
Vue.use(VuePageTransition)

library.add(faUserCircle, faSignInAlt, faBars, faTimes, faEye, faEyeSlash, faUpload, 
    faTrash, faBan, faSearch, faExclamationCircle, faEdit,faFileAudio,
    faFileImage,faFileVideo, faPlus, faMinus,faThumbsUp,faFlag, faChevronDown,
    faComment, faChevronLeft);


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
// Vue.component('view-comments', require('./components/ViewComments.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    router,
    store,
    created(){
        const token = TokenService.getToken()
        if (token) {
            
            ApiService.setHeaderAuth()
            store.dispatch('reloadUser')
        } 
        else {
            
        }
        ApiService.mount401Interceptor()
    }
});
