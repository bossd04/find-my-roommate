import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo WebSocket is disabled - enable when Laravel Reverb server is running
 * To start Reverb server: php artisan reverb:start
 */
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: 'local',
//     wsHost: window.location.hostname,
//     wsPort: 8080,
//     forceTLS: false,
//     disableStats: true,
// });
