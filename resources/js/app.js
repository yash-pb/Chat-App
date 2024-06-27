import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from "./routes/index";
import { createPinia } from 'pinia'
import axios from 'axios';
import "./plugins/echo";

const pinia = createPinia()
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';

// createApp(App).use(router, pinia, axios).mount('#app');
const app = createApp(App)
app.use(router)
app.use(pinia)
// app.use(axios)
app.mount('#app')
