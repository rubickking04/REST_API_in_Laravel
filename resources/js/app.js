import './bootstrap';
import { createApp , onMounted } from 'vue';
import app from './components/app.vue';
import router from './router';
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ,
    encrypted: true,
    authEndpoint: '/refresh-database',
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/refresh-database', {
                    socket_id: socketId,
                    channel_name: channel.name
                })
                    .then(response => {
                        callback(false, response.data)
                    })
                    .catch(error => {
                        callback(true, error)
                    })
            }
        }
    }
})
createApp(app).use(router).mount("#app");
