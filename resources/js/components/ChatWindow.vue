<template>
  <div class="flex-1 flex flex-col">
    <div class="p-4 bg-gray-800 text-white flex items-center justify-between">
      <h2 class="text-xl font-bold">{{ friend.name ?? 'Chat' }}</h2>
      <button class="text-gray-400 hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h12M6 12l6 6m-6-6l6-6" />
        </svg>
      </button>
    </div>
    <div id="auto-scroll" class="flex-1 auto-scroll overflow-y-auto p-4 bg-gray-100 space-y-4" v-if="messagesComputed">
      <div v-for="message in messagesComputed" :key="message.id" :class="messageClass(message)">
        <p class="text-sm">{{ message.text }}</p>
        <p class="text-xs text-gray-400">{{ formatTimestamp(message.created_at) }}</p>
      </div>
    </div>
    <div class="p-4 bg-gray-200 flex items-center sticky top-[100vh]">
      <input v-model="newMessage" @keyup.enter="sendMessage" :disabled="!friend.id"
        class="flex-1 p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500"
        placeholder="Type a message...">
      <button @click="sendMessage"
        class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Send</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Echo from 'laravel-echo';
import { useUserStore } from "../stores/user";

const userStore = useUserStore();
const props = defineProps(['messages', 'friend', 'room']);
const newMessage = ref('');
// const selectedUserId = ref(props.friend.id); // This should be set to the user you're chatting with
const roomId = computed(() => {
  return props.room;
})

const selectedUserId = computed(() => {
  return props.friend.id;
})

const listenToRoom = (room_id, old_room_id) => {
  const echo = new Echo({
    broadcaster: 'pusher',
    key: '42b422186b913915b461',
    authEndpoint: '/broadcasting/auth',
    // authEndpoint: '/api/broadcasting/auth',
    cluster: 'ap2',
    encrypted: true,
    auth: {
      headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
        Authorization: 'Bearer ' + localStorage.getItem('token'),
      },
    },
  });

  // console.log('Object.keys => ', Object.keys(old_room_id));
  // if(old_room_id != null) {
  //   console.log('old_room_id => ', old_room_id);
  // }
  echo.leave(`chat-room.${old_room_id}`);

  console.log('room_id => ', room_id);
  echo.private(`chat-room.${room_id}`)
  .listen('.chat-room', (e) => {
    console.log('e => ', e,room_id);
    if(room_id == e.message.room_id) {
      props.messages.push(e.message)
    }
    // messages.value.push(e.message);
  });
};

watch(roomId, async (newRoomId, oldRoomId=null) => {
  console.log('newRoomId => ', newRoomId, roomId.value);
  if (newRoomId) {
    // const response = await axios.get(`/chat-room/${newUserId}/room-id`);
    // roomId.value = response.data.room_id;
    // await fetchMessages(roomId.value);
    listenToRoom(roomId.value, oldRoomId);
  }
});

const sendMessage = async () => {
  if (newMessage.value.trim() !== '') {
    fetch('http://127.0.0.1:8000/api/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ message: newMessage.value, 'receiver_id': selectedUserId.value, room_id: roomId.value }),
    }).then(() => {
      newMessage.value = '';
    });
  }
};

// onMounted(() => {
//   // Example: setting selectedUserId to user2's ID to start chatting with user2
//   selectedUserId.value = props.friend.id;
// });

const messagesComputed = computed(() => {
  return props.messages;
})

const messageClass = (message) => {
  return message.sender_id !== userStore.user.id
    ? 'self-end bg-blue-500 text-white p-3 rounded-lg shadow-md'
    : 'self-start bg-gray-300 text-black p-3 rounded-lg shadow-md';
};

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');

  return `${year}-${month}-${day} ${hours}:${minutes}`;
}
</script>

<!-- <script setup>
import { ref, onMounted, watch, computed } from 'vue';
import { useUserStore } from "../stores/user";

const userStore = useUserStore();
const props = defineProps(['messages', 'friend', 'room']);
const newMessage = ref('');
const prevRoom = ref(null);


const messagesComputed = computed(() => {
  return props.messages;
})

const roomComputed = computed(() => {
  return props.room;
})

const sendMessage = () => {
  if (newMessage.value.trim() !== '') {
    fetch('http://127.0.0.1:8000/api/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ message: newMessage.value, 'receiver_id': props.friend.id, room_id: props.room }),
    }).then(() => {
      newMessage.value = '';
    });
  }
};

const messageClass = (message) => {
  return message.sender_id !== userStore.user.id
    ? 'self-end bg-blue-500 text-white p-3 rounded-lg shadow-md'
    : 'self-start bg-gray-300 text-black p-3 rounded-lg shadow-md';
};

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');

  return `${year}-${month}-${day} ${hours}:${minutes}`;
}

watch(roomComputed, (newRoom, oldRoom) => {







  // Echo.private(`chat.${newRoom.room_id}`)
  // .listen('.chat', (e) => {
  //   console.log('e => ', e);
  //   // this.messages.push(e.message);
  // });




  // console.log('oldRoom => ', oldRoom, newRoom);
  // if(newRoom.length > 0 && oldRoom != undefined) {
  //   Echo.leave(`chat.${oldRoom.room_id}`);
    // Echo.channel(`chat.${newRoom.room_id}`)
    // .listen('.chat', (e) => {
    //   console.log('=> => ', e.room, newRoom);
    //   if(e.room.room_id == newRoom.room_id) {
    //     props.messages.push(e.message);
    //   }
    // });


    // console.log('Echo => ', Echo.private('chat.123456'));
    // Echo.channel(`chat.${newRoom.room_id}`)
    // // Echo.channel(`chat.123456`)
    // .listen('.chat', (e) => {
    //   console.log('=> => ', e.room, newRoom);
    //   if(e.room.room_id == newRoom.room_id) {
    //     props.messages.push(e.message);
    //   }
    // });
  // }
},{ immediate: true })

// onMounted(() => {
//   document.getElementById("auto-scroll").scrollIntoView()
// })
</script> -->