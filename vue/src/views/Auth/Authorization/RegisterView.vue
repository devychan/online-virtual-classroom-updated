<template>
    <div class="login-frame">
        <form class="form-login" @submit.prevent="_createUser(user)">
            <div class="input-group">
                <h1>REGISTRATION</h1>
            </div>
            <div class="message">{{ _message ? _message : '' }}</div>
            <div class="input-group">
                <input type="text" name="name" class="form-input" placeholder="NAME" v-model="user.name">
            </div>
            <div class="error">{{ _errors.name ? _errors.name[0] : '' }}</div>
            <div class="input-group">
                <input type="text" name="username" class="form-input" placeholder="USERNAME" v-model="user.username">
            </div>
            <div class="error">{{ _errors.username ? _errors.username[0] : '' }}</div>
            <div class="input-group">
                <input type="password" name="password" class="form-input" placeholder="PASSWORD"
                    v-model="user.password">
            </div>
            <div class="error">{{ _errors.password ? _errors.password[0] : '' }}</div>
            <div class="input-group">
                <router-link :to="{ name: 'Login' }" style="color:#EEEDDE">Login an account?</router-link>
            </div>
            <div class="input-group">
                <button type="submit">CONTINUE</button>
            </div>
        </form>
    </div>
</template>
<script setup>
import { ref } from 'vue'

const props = defineProps({
    _createUser: Function,
    _errors: Object,
    _message: String
})

const user = ref({
    name: '',
    username: '',
    password: '',
    image_path:null
})
</script>
<style scoped>
.message {
    color: #EEEDDE;
}

.error {
    color: #D63447;
}

.form-login button {
    padding: 15px;
    width: 100%;
    border: 0px;
    border-radius: 4px;
    cursor: pointer;
    background-color: #1B2430;
    color: #EEEDDE;
}

.form-login button:hover {
    box-shadow: 1px 1px 8px;
}

.form-login {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    width: 350px;
    background-color: #1B2021;
    border-radius: 10px;
}

.login-frame {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 10%;
}

.input-group {
    display: flex;
    align-items: center;
    padding: 5px;
    width: 100%;
    border-radius: 4px;
    margin: 5px 0px 5px 0px;
}

.input-group h1 {
    font-size: 28px;
    color: #EEEDDE;
}

.form-input {
    padding: 14px;
    border: 0px;
    border-radius: 4px;
    background-color: #F5F5F5;
    width: 100%;
    font-size: 17px;
}

.form-input:focus {
    background-color: #F6F6F6;
}
</style>