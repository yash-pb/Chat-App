import { defineStore } from "pinia"
import { computed, ref } from 'vue'

export const useUserStore = defineStore("user", () => {
    const user = ref({})
    const token = ref(localStorage.getItem('token') || null)

    const setDatas = async(newToken, userData) => {
        token.value = newToken;
        // console.log('user => => ', typeof user);
        if(typeof userData == 'string')
            user.value = JSON.parse(userData);
        else
            user.value = userData;
    };

    const checkLogin = async(credentials) => {
        axios
        .post('login', credentials)
        .then(async (response) => {
            if(response.status === 200) {
                localStorage.setItem('token', response.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.user));
                setDatas(response.data.token, response.data.user);
                return true
            }
            return false
        })
        .catch((err) => {
            console.log(err);
            if (err.response.status === 422) {
                return  err.response.data.message;
            }
        })
        // .finally(() => this.loading = false)
    }

    return {
        token,
        user,
        setDatas,
        checkLogin
        // fetchUser
    }
})