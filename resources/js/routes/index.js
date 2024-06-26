import { createWebHistory, createRouter } from "vue-router";
import routes from "./routes";
import { useUserStore } from "../stores/user";

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const userStore = useUserStore();
    if (to.meta.requiresAuth) {
        const token = localStorage.getItem('token');
        if (token) {
        userStore.setDatas(token, localStorage.getItem('user'));
        next();
        } else {
        next({name: 'login'});
        }
    } else {
        next();
    }
});

export default router;