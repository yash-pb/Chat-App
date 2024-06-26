<template>
    <div class="flex h-screen">
        <Sidebar :friends="friends" @openUserChatWindow="handleOpenUserChatWindow" />
        <ChatWindow :messages="messages" :friend="friend" />
    </div>
</template>
<script setup>
import { useUserStore } from "../stores/user";
import Sidebar from '../components/Sidebar.vue'
import ChatWindow from '../components/ChatWindow.vue'
import { computed } from "vue";
import { ref, onMounted } from 'vue';
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

const userStore = useUserStore();
const user = computed(() => {
  return userStore.user;
})

// console.log('user => ', user);

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: '42b422186b913915b461',
//     cluster: 'ap2',
//     encrypted: true,
// });

// Echo.channel('chat')
// .listen('.new.message', (message) => {
//     // Update messages state or UI to display the new message
//     console.log('New message received:', message);
// });

const messages = ref([]);
const friends = ref([]);
const friend = ref({});
const newMessage = ref('');

// const echo = new Echo({
//   broadcaster: 'pusher',
//   key: '42b422186b913915b461',
//   cluster: 'ap2',
//   forceTLS: true,
// });

// console.log('echo => ', echo);
onMounted(() => {
    window.Echo.channel('chat')
    .listen('.new.message', (message) => {
        // Update messages state or UI to display the new message
        messages.value.push(message);
    });

//   echo.channel('chat')
//     .listen('MessageSent', (e) => {
//       messages.value.push(e.message);
//     });

    // fetch friends
    fetch('http://127.0.0.1:8000/api/friends', {
        headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => response.json())
    .then(data => {
        friends.value = data.friends;
        console.log('friends => ', friends.value);
    });

  // Fetch existing messages from the server
    
});

const friendFetchMsg = (friendId) => {
    fetch('http://127.0.0.1:8000/api/get-messages', {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({ 'friend_id': friendId }),
    })
    .then(response => response.json())
    .then(data => {
        console.log('get chat data => ',data);
        messages.value = data.messages;
        friend.value = data.friend;
    });
}

const sendMessage = () => {
  fetch('http://127.0.0.1:8000/api/messages', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${localStorage.getItem('token')}`
    },
    body: JSON.stringify({ message: newMessage.value, 'receiver_id': 2 }),
  }).then(() => {
    newMessage.value = '';
  });
};

const handleOpenUserChatWindow = (friendId) => {
    // console.log('from chat => ', friendId);
    friendFetchMsg(friendId);
}

</script>