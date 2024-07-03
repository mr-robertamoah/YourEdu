import { default as _ } from 'lodash';
window._ = _;

import axios from 'axios';
window.axios = axios;

window.axios.defaults.baseURL = `${import.meta.env.VITE_APP_URL}/api`
window.axios.defaults.withCredentials = true
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import 'bootstrap';

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

} catch (e) {}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import StorageService from './services/storage.service';
import { TokenService } from './services/token.service';

window.YoureduStorage = new StorageService('localStorage')

import pusherJs from 'pusher-js';
window.Pusher = pusherJs;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     // encrypted: true,
//     wsHost: import.meta.env.VITE_PUSHER_APP_HOST,
//     wsPort: 6001,
//     forceTLS:false,
//     auth: {
//         headers: {
//             Authorization: `Bearer ${TokenService.getToken()}`
//         }
//     }
//     // disableStats: true
// });
