<template>
    <div class="profile-container">
        <div class="container" v-if="_status">
            <div class="profile-title">
                <h1>User Info</h1>
            </div>
            <div class="profile-manager">
                <form class="user-form" ref="form" @submit.prevent="updateUser($refs)">
                    <div style="padding: 10px;background-color: #50CB93;text-align: center;" v-if="msg">
                        <b style="padding: 5px;">{{ msg }}</b>
                    </div>

                    <!-- <div class="input-group" v-if="userOnUpdate.image_path">
                        <img :src="imageOnDisplay" width="200">
                    </div>
                    <div class="input-group">
                        <input type="file" class="input-text" accept=".jpeg, .png, .jpg" name="image_path" ref="image" @change="addImage($refs)">
                    </div> -->
                    <div class="input-group">
                        <input type="text" placeholder="Name" class="input-text" :value="_user.name" name="name"
                            :style="errors.name ? 'border: 2px solid #F94C66;' : ''" :disabled="statusField">
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="Username" class="input-text" :value="_user.username"
                            name="username" :style="errors.username ? 'border: 2px solid #F94C66;' : ''"
                            :disabled="statusField">

                    </div>
                    <div class="input-group">
                        <input type="password" placeholder="Current password" class="input-text" name="password"
                            :style="errors.password ? 'border: 2px solid #F94C66;' : ''" :disabled="statusField">

                    </div>
                    <div class="input-group">
                        <input type="password" placeholder="New password" class="input-text" name="new_password"
                            :disabled="statusField">
                    </div>

                    <div class="input-group" style="display:flex; flex-direction: row-reverse;">
                        <div class="edit"
                            :style="!statusField ? 'background-color:#EB5353' : 'background-color:#1B2430'"
                            @click="statusField = !statusField">
                            <b style="color:#ffff;">Edit</b>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="submit" value="Save" class="input-text">
                    </div>
                </form>
            </div>
        </div>
        <div class="error-msg" v-if="errorStatus">
            <div class="error-title" v-for="(error, index) in errors"> {{ error[0] }}</div>
        </div>
        <!-- <div class="error-msg" v-else-if="msgStatus && success" style="background-color: #6BCB77;">
            <div class="error-title" style="color:#1B2430"> {{ msgStatus }}</div>
        </div> -->
    </div>
</template>


<script setup>
import { reactive, ref, onMounted, nextTick } from 'vue';
import router from '../../../../router';
import store from '../../../../store/index'
const props = defineProps({
    _user: Object,
    _status: Boolean,
    _reRender: Function
})
const userOnUpdate = ref({
    name: '',
    username: '',
    image_path: null
})
const errors = ref({})
const msg = ref()
const errorStatus = ref(false)
const statusField = ref(true)
const imageOnDisplay = ref()
const addImage = (data) => {
    const image = data.image.files[0]
    const reader = new FileReader();
    reader.onload = () => {
        userOnUpdate.value.image_path = reader.result    
    }
    reader.readAsDataURL(image)
    imageOnDisplay.value = URL.createObjectURL(image)
}
const updateUser = async (data) => {
    let form = new FormData(data.form)
    // const formData = reactive({
    //     name: form.get('name'),
    //     username: form.get('username'),
    //     password: form.get('password'),
    //     new_password: form.get('new_password'),
    //     image_path: userOnUpdate.value.image_path
    // })
    const response = await store.dispatch('updateUser', form)
    if (!response.errors) {
        msg.value = response
        props._reRender()
        setTimeout(() => {
            msg.value = ""
            router.push({
                name: 'Profile'
            })
        }, 2000);
    } else {
        errorStatus.value = !errorStatus.value
        errors.value = response.errors
        setTimeout(() => {
            errors.value = {}
            errorStatus.value = !errorStatus.value
        }, 3000);
    }
}
onMounted(() => {

})
</script>
<style scoped>
.edit {
    padding: 10px;
    border: 0px;
    font-size: 15px;
    box-shadow: 1px 1px 5px;
    background-color: #1363DF;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.error-msg {
    position: absolute;
    top: 0%;
    right: 0%;
    padding: 10px;
    margin: 10px 20px 0px 5px;
    background-color: #D82148;
    box-shadow: 1px 1px 8px;
    animation: fadeIn .5s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.error-title {
    padding: 10px;
    font-size: 18px;
    color: #F5F3F5;
    width: auto;
}

.input-text {
    padding: 15px;
    width: 100%;
    border: 0px;
    font-size: 15px;
    box-shadow: 1px 1px 5px;
}

.input-text:focus {
    outline: none;
}

.input-text:hover {
    box-shadow: 1px 1px 7px #1B2430;
}

.input-group svg {
    width: 30px;
    height: 30px;
}

input[type=submit] {
    box-shadow: 1px 1px 5px;
    cursor: pointer;
    border: 0px;
    background-color: #1363DF;
    color: #F5F3F5;
}

input[type=submit]:hover {
    box-shadow: 1px 1px 7px #1B2430;
}

.input-group {
    padding: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

* {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.user-action {
    padding: 10px;
}

.profile-manager {
    padding: 10px;
}

.profile-title {
    padding: 5px;
    display: grid;
    place-content: center;
    width: 100%;

}

.container {
    margin-top: 5%;
    padding: 20px;
    background-color: #F5F3F5;
    box-shadow: 1px 1px 8px;
    width: 100%;
}

.profile-container {
    display: grid;
    grid-template-columns: auto;
    place-items: center;
    place-content: center;
}

header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #1B2430;
    box-shadow: 1px 2px 7px;
}

header h1 {
    text-align: center;
    color: #F5F3F5;
}
</style>