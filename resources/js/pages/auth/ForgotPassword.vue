
<template>
    <div class="flex h-screen min-h-full flex-col justify-center px-6 py-12 lg:px-8 bg-neutral-200">        
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-5 py-5 rounded-lg">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h1 class="text-2xl font-bold text-center">Book Store</h1>
                <h2 class="mt-7 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Forgot Password</h2>
            </div>
            <div v-if="message" class="p-4 mb-4 my-2 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ message }}
            </div>
            <form class="space-y-5" method="POST" @submit.prevent="forgotPass">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                    <div class="mt-2">
                        <input id="email" v-model="credentials.email" name="email" type="email" placeholder="Enter email" required class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <p class="error text-red-500 text-xs italic" v-if="errors?.email">{{ errors.email[0] }}</p>
                    </div>
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-white w-full">Send Mail</button>
                </div>

                <div class="text-sm text-center">
                    <router-link :to="{name:'login'}">
                        <a class="font-semibold text-indigo-600 hover:text-indigo-500">Log in</a>
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            credentials: {},
            errors: '',
            message: ''
        }
    },
    methods: {
        async forgotPass() {
            axios.post('forgot-password', this.credentials)
            .then(response => {
                console.log('response => ', response);
                if(response.status === 200) {
                    // this.$router.push({ name: 'login' });
                    this.message = response.data.message;
                }
            })
            .catch((e) => {
                if (e.response.status === 422) {
                    for (const key in e.response.data.errors) {
                        this.errors = e.response.data.errors
                    }
                }
            })
            .finally(() => this.loading = false)
        }
    }
}
</script>