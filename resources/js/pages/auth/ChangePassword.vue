
<template>
    <div class="flex h-screen min-h-full flex-col justify-center px-6 py-12 lg:px-8 bg-neutral-200">        
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white px-3 py-3 rounded-lg">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h1 class="text-2xl font-bold text-center">Book Store</h1>
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Update Password</h2>
            </div>
            <form class="space-y-5" method="POST" @submit.prevent="updatePass">
                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" v-model="credentials.password" name="password" type="password" placeholder="Enter password" required class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <p class="error text-red-500 text-xs italic" v-if="errors?.password">{{ errors.password[0] }}</p>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    <div class="mt-2">
                        <input id="password_confirmation" v-model="credentials.password_confirmation" name="password_confirmation" type="password" placeholder="Enter confirm password" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <p class="error text-red-500 text-xs italic" v-if="errors?.password_confirmation">{{ errors.password_confirmation[0] }}</p>
                    </div>
                </div>


                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded text-white w-full">Update Password</button>
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
            token: this.$route.query && this.$route.query.token ? this.$route.query.token : ''
        }
    },
    methods: {
        async updatePass() {
            // console.log(this.credentials);
            axios.post(`change-password/${this.token}`, this.credentials)
            .then(response => {
                console.log('response => ', response);
                if(response.status === 200) {
                    this.$router.push({ name: 'login' });
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
    },
    mounted() {
        console.log();
    }
}
</script>