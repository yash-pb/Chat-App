import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from "./routes/index";
// import VueAxios from 'vue-axios';
// import axios from 'axios';
// import { createPinia, setActivePinia } from 'pinia'

// axios.defaults.withCredentials = true;
// axios.defaults.baseURL = 'http://127.0.0.1:8000/admin/vue/api/';

// const pinia = createPinia();
// setActivePinia(pinia);

// createApp(App).use(router, pinia, VueAxios, axios).mount('#app');
createApp(App).use(router).mount('#app');
