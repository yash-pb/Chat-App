import { useUserStore } from "../stores/user";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

setTimeout(() => {
    const userStore = useUserStore();
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '42b422186b913915b461',
        cluster: 'ap2',
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
                Authorization: `Bearer ${userStore.token}`
            },
        },
    });
}, 100);
