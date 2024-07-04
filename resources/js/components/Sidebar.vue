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
        <div class="search-section m-2">
          <div class="search-box">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input :class="searchResult.length > 0 ? 'rounded-t-lg' : 'rounded-lg'" @keyup.enter="searchUsers" v-model="searchUser" type="search" id="default-search" class="block w-full p-2 ps-10 text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white" placeholder="Search user by user_name..." />
            </div>
          </div>
          <div class="search-results">
            <div class="z-10 w-full border divide-y shadow max-h-72 overflow-y-auto text-black bg-white" v-if="searchResult.length > 0">
              <div v-for="user in searchResult" :key="user.id" @click="openUserChat(user.id)" class="p-4 border-b border-gray-700 hover:bg-gray-700 hover:text-white cursor-pointer flex items-center space-x-4">
                <img :src="user.avatar" alt="avatar" class="w-10 h-10 rounded-full">
                <div>
                  <p class="font-semibold">{{ user.name }}</p>
                  <p class="text-gray-400 text-sm">{{ user.lastMessage }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
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
    searchResult.value = [];
    searchUser.value = '';
    emits('openUserChatWindow', friendId);
  }

  const searchToggle = () => {
    isSearchVisible.value = !isSearchVisible.value;
  }

  const searchUsers = () => {
    fetch(`http://127.0.0.1:8000/api/search-user?search=${searchUser.value}`, {
        headers: {
        'Authorization': `Bearer ${userStore.token}`
        }
    })
    .then(response => response.json())
    .then(data => {
      searchResult.value = data.users;
    });
  }
  </script>
  