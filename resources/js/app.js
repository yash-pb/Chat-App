import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from "./routes/index";
import { createPinia } from 'pinia'
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const pinia = createPinia()
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';

// createApp(App).use(router, pinia, axios).mount('#app');
const app = createApp(App)
app.use(router)
app.use(pinia)

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '42b422186b913915b461', // Replace with your Pusher key
    cluster: 'ap2', // Replace with your Pusher cluster
    encrypted: true,
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}` // Adjust according to your authentication
        }
    }
});

// app.use(axios)
app.mount('#app')
