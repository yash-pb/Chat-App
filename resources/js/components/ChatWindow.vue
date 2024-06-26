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
      <div class="flex-1 overflow-y-auto p-4 bg-gray-100 space-y-4" v-if="messages">
        <div v-for="message in messages" :key="message.id" :class="messageClass(message)">
          <p class="text-sm">{{ message.text }}</p>
          <p class="text-xs text-gray-400">{{ formatTimestamp(message.created_at) }}</p>
        </div>
      </div>
      <div class="p-4 bg-gray-200 flex items-center sticky top-[100vh]">
        <input v-model="newMessage" @keyup.enter="sendMessage" class="flex-1 p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500" placeholder="Type a message...">
        <button @click="sendMessage" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Send</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useUserStore } from "../stores/user";
    const userStore = useUserStore();
  
  const props = defineProps(['messages', 'friend']);

//   const messages = ref([
//     { id: 1, text: 'Hello!', time: '10:00 AM', sender: 'me' },
//     { id: 2, text: 'Hi! How are you?', time: '10:01 AM', sender: 'other' },
//   ]);

// console.log('messages => => ', props);
  
  const newMessage = ref('');
  
  const sendMessage = () => {
    if (newMessage.value.trim() !== '') {
    //   messages.value.push({ id: Date.now(), text: newMessage.value, time: new Date().toLocaleTimeString(), sender: 'me' });
    //   newMessage.value = '';
        fetch('http://127.0.0.1:8000/api/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({ message: newMessage.value, 'receiver_id': props.friend.id }),
        }).then(() => {
            newMessage.value = '';
        });
    }
  };
  
  const messageClass = (message) => {
    console.log('in cls => ', message, userStore.user);
    // return message.sender_id.id !== userStore.user.id
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
    const seconds = String(date.getSeconds()).padStart(2, '0');
    
    return `${year}-${month}-${day} ${hours}:${minutes}`;
  }
  </script>
  