<template>
    <div class="w-1/4 bg-gray-900 text-white flex flex-col">
      <div class="p-4 flex items-center justify-between bg-gray-800">
        <h1 class="text-xl font-bold">
          <input @keyup.enter="searchUsers" v-if="isSearchVisible" class="text-gray-900 w-56 text-base" type="text" name="search" id="search" placeholder="Search User" v-model="searchUser">
          <span v-else>
            {{ userStore.user.name }}
          </span>
        </h1>
        <button @click="searchToggle" class="text-gray-400 hover:text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </button>
      </div>
      <div class="flex-1 overflow-y-auto" v-if="friends">
        <div v-for="frined in friends" :key="frined.id" @click="openUserChat(frined.id)" class="p-4 border-b border-gray-700 hover:bg-gray-700 cursor-pointer flex items-center space-x-4">
          <img :src="frined.avatar" alt="avatar" class="w-10 h-10 rounded-full">
          <div>
            <p class="font-semibold">{{ frined.name }}</p>
            <p class="text-gray-400 text-sm">{{ frined.lastMessage }}</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useUserStore } from "../stores/user";

  const props = defineProps(['friends']);
  const emits = defineEmits(['openUserChatWindow']);
  const userStore = useUserStore();
  const searchUser = ref('');
  const isSearchVisible = ref(false);
  const searchResult = ref([]);
  
  const openUserChat = (friendId) => {
    emits('openUserChatWindow', friendId);
  }

  const searchToggle = () => {
    isSearchVisible.value = !isSearchVisible.value;
  }

  const searchUsers = () => {
    fetch(`http://127.0.0.1:8000/api/search-user?search=${searchUser.value}`, {
        headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
        }
    })
    .then(response => response.json())
    .then(data => {
      console.log('data => ', data);
      searchResult.value = data.users;
      console.log('searchResult => ', searchResult);
    });
  }
  </script>