import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
 
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '42b422186b913915b461',
    cluster: 'ap2',
    forceTLS: true,
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}` // Adjust according to your authentication
        }
    }
});