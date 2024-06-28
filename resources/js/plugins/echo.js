// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;
 
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: '42b422186b913915b461',
//     cluster: 'ap2',
//     forceTLS: true,
//     auth: {
//         headers: {
//             Authorization: `Bearer ${localStorage.getItem('token')}` // Adjust according to your authentication
//         }
//     },
//     bearerToken: `Bearer ${localStorage.getItem('token')}`
// });


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '42b422186b913915b461',
    cluster: 'ap2',
    encrypted: true,
    authEndpoint: '/api/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
            Authorization: 'Bearer ' + localStorage.getItem('token'),
        },
    },
});
