
<template>
    <div class="flex h-screen min-h-full flex-col justify-center px-6 py-12 lg:px-8 bg-neutral-200">
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-5 py-5 rounded-lg">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h1 class="text-2xl font-bold text-center">App</h1>
                <h2 class="mt-7 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
            </div>
            <div v-if="message" class="p-4 mb-4 my-2 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {{ message }}
            </div>
            <form @submit.prevent="checkLogin" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <div class="mt-2">
                        <input id="email" v-model="credentials.email" type="email" placeholder="Enter email" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
        
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" v-model="credentials.password" name="password" type="password" placeholder="Enter password" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="text-sm text-right">
                    <router-link :to="{name:'forgot.password'}">
                        <a data-modal-target="default-modal" data-modal-toggle="default-modal" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                    </router-link>
                </div>
        
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-white w-full">Sign in</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import { useUserStore } from "../../stores/user";
import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const userStore = useUserStore();

const credentials = ref({});
const message = ref('');
const checkLogin = async () => {
    let result = userStore.checkLogin(credentials.value);
    if(result) {
        router.push({ name: 'chat' });
    }
}
</script>
