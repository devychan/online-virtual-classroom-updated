<template>
    <div class="index" v-if="!loginPending">
        <router-view :_createUser="createUser" :_loginUser="loginUser" :_errors="errors" :_message="message">
        </router-view>
    </div>
    <div class="index" v-else>
        <p>redirecting...</p>
    </div>
</template>
<script setup>
import router from '../../../../router';
import store from '../../../../store';
import { nextTick, onMounted, ref } from 'vue';
import client from '../../../Axios';
import cookie from '../../../Cookie';

const errors = ref({})
const message = ref("")
const loginPending = ref(null)

onMounted(async () => {
    loginPending.value = false
})

const createUser = async (data) => {
    console.clear()
    await cookie()
    return client.post('/register', data).then((response) => {
        setTimeout(() => {
            errors.value = {}
        }, 2000);
        message.value = response.data.message
        setTimeout(() => {
            router.push({ name: 'Dashboard' })
        }, 2000);
    }).catch((err) => {
        errors.value = err.response.data.errors
        setTimeout(() => {
            errors.value = {}
        }, 2000);
    })

}
const loginUser = async (data) => {
    console.clear()
    await cookie()
    await nextTick(async () => {
        loginPending.value = true
    })
    return client.post('/login', data).then((response) => {
        if (response) {
            store.state.data.user = response.data.result
            store.state.data.api_token = response.data.token
            sessionStorage.setItem('auth', "true")
            sessionStorage.setItem('token', response.data.token)
            setTimeout(() => {
                errors.value = {}
            }, 2000);
            return router.push({ name: 'Dashboard' })
        }

    }).catch((err) => {
        errors.value = err.response.data.errors
        setTimeout(() => {
            errors.value = {}
        }, 2000);
        loginPending.value = false
    })
}
</script>
<style scoped>
.index {
    animation: fade 1s;
}

@keyframes fade {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}
</style>